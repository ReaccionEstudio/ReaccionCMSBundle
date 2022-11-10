<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\Comment;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use ReaccionEstudio\ReaccionCMSBundle\Entity\User;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Comment;
use ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;
use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Comments service.
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class CommentService
{
    /**
     * @var EntityManagerInterface
     *
     * EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     *
     * TranslatorInterface
     */
    private $translator;

    /**
     * @var Session
     *
     * Session
     */
    private $session;

    /**
     * @var RequestStack
     *
     * RequestStack service
     */
    private $request;

    /**
     * @var ConfigServiceInterface
     *
     * Config service
     */
    private $config;

    /**
     * @var User
     *
     * User entity
     */
    private $user = null;

    /**
     * @var LoggerServiceInterface
     *
     * Logger service
     */
    private $logger;

    /**
     * CommentService constructor.
     * @param EntityManagerInterface $em
     * @param Session $session
     * @param TranslatorInterface $translator
     * @param RequestStack $request
     * @param ConfigServiceInterface $config
     * @param LoggerServiceInterface $logger
     */
    public function __construct(
        EntityManagerInterface $em,
        Session $session,
        TranslatorInterface $translator,
        RequestStack $request,
        ConfigServiceInterface $config,
        LoggerServiceInterface $logger)
    {
        $this->em = $em;
        $this->session = $session;
        $this->translator = $translator;
        $this->config = $config;
        $this->request = $request->getCurrentRequest();
        $this->logger = $logger;
    }

    /**
     * Get comments list
     * @param int $entryId
     * @return array
     */
    public function getComments(int $entryId): array
    {
        $page = ($this->request->query->get('cp')) ?? 1;
        $getCommentsAsArray = new GetCommentsAsArray($this->em, $entryId, $page, $this->config);
        return $getCommentsAsArray->getComments();
    }

    /**
     * Post a new comment
     *
     * @param Entry $entry
     * @param string $comment
     * @param null $user
     * @param null $replyTo
     * @return int
     */
    public function post(Entry $entry, string $comment, $user = null, $replyTo = null): int
    {
        try {
            // sanitize comment
            $comment = (new CommentSanitizer($comment))->getContent();

            // create a new comment entity
            $commentEntity = new Comment();
            $commentEntity->setEntry($entry);
            $commentEntity->setUser($user);
            $commentEntity->setContent($comment);

            if ($replyTo != null) {
                // set parent
                /** @var Comment $parentCommentEntity */
                $parentCommentEntity = $this->em->getRepository(Comment::class)->findOneBy(['id' => $replyTo]);

                if ($parentCommentEntity) {
                    $commentEntity->setReply($parentCommentEntity);
                }

                // set root value
                $root = $this->getCommentRoot($commentEntity);

                if ($root) {
                    $commentEntity->setRoot($root);
                } else if (!$root && $parentCommentEntity) {
                    $commentEntity->setRoot($parentCommentEntity);
                }
            }

            $this->em->persist($commentEntity);
            $this->em->flush();

            // increase comment count
            $this->updateCommentsCount($entry, $replyTo, "+");

            // success message
            if ($replyTo == null) {
                $successMssg = $this->translator->trans('entries_comments.comment_posted_successfully');
                $this->session->getFlashBag()->add('comment_success', $successMssg);
            } else {
                $successMssg = $this->translator->trans('entries_comments.reply_posted_successfully');
                $this->session->getFlashBag()->add('post_reply_success', $successMssg);
            }

            return $commentEntity->getId();
        } catch (\Exception $e) {
            // error message
            $errorMssg = $this->translator->trans(
                'entries_comments.comment_posted_error',
                ['%error%' => $e->getMessage()]
            );
            $this->session->getFlashBag()->add('comment_error', $errorMssg);

            // log
            $this->logger->logException($e);
            return 0;
        }
    }

    /**
     * Remove comment
     *
     * @param  Comment $comment Comment entity
     */
    public function remove(Comment $comment): bool
    {
        try {
            $entry = $comment->getEntry();

            // remove
            $this->em->remove($comment);
            $this->em->flush();

            // decrease comment count
            $this->updateCommentsCount($entry, false, "-");

            // success message
            $successMssg = $this->translator->trans('entries_comments.comment_removed_successfully');
            $this->session->getFlashBag()->add('remove_comment_success', $successMssg);

            return true;
        } catch (\Exception $e) {
            // error message
            $errorMssg = $this->translator->trans(
                'entries_comments.comment_removed_error',
                ['%error%' => $e->getMessage()]
            );
            $this->session->getFlashBag()->add('comment_error', $errorMssg);

            // log error
            $this->logger->logException($e);
            return false;
        }
    }

    /**
     * Update comment
     *
     * @param Comment $comment
     * @param string $content
     * @return Comment
     */
    public function update(Comment $comment, string $content)
    {
        try {
            $comment->setContent($content);

            // save
            $this->em->persist($comment);
            $this->em->flush();

            // success message
            $successMssg = $this->translator->trans('entries_comments.comment_updated_successfully');
            $this->session->getFlashBag()->add('comment_success', $successMssg);

            return $comment;
        } catch (\Exception $e) {
            // log error
            $this->logger->logException($e);
        }
    }

    /**
     * Increase 'totalComments' count for Entry entity
     * @param Entry $entry
     * @param bool $isReply
     * @param string $operator
     */
    public function updateCommentsCount(Entry $entry, bool $isReply = false, string $operator = "+")
    {
        $updateEntryCommentsCount = new UpdateEntryCommentsCount($this->em, $entry, $this->logger);

        if ($operator == "+") {
            $updateEntryCommentsCount->increase($isReply);
        } else if ($operator == "-") {
            $updateEntryCommentsCount->decrease($isReply);
        }
    }

    /**
     * Get comment root value
     *
     * @param Comment $comment
     * @return Comment
     */
    public function getCommentRoot(Comment $comment)
    {
        if (empty($comment->getReply())) {
            return $comment;
        } else {
            $reply = $comment->getReply();
            return $reply->getRoot();
        }
    }
}
