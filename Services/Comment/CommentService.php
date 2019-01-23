<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment;
	
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Comment;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment\CommentSanitizer;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment\GetCommentsAsArray;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment\UpdateEntryCommentsCount;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Cache\PageCacheService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

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
		 * @var ConfigService
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
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Session $session, TranslatorInterface $translator, RequestStack $request, ConfigService $config, PageCacheService $pageCacheService, LoggerServiceInterface $logger)
		{
			$this->em 				= $em;
			$this->session 			= $session;
			$this->translator 		= $translator;
			$this->config 			= $config;
			$this->request 			= $request->getCurrentRequest();
			$this->pageCacheService	= $pageCacheService;
			$this->logger 			= $logger;
		}

		/**
		 * Get comments list
		 *
		 * @param  Integer  $entryId 	Entry ID
		 * @return Array 	[type]		Comments array
		 */
		public function getComments(Int $entryId) : Array
		{
			$page = ($this->request->query->get('cp')) ?? 1;
			$getCommentsAsArray = new GetCommentsAsArray($this->em, $entryId, $page, $this->config);
			return $getCommentsAsArray->getComments();
		}

		/**
		 * Post a new comment
		 *
		 * @param  Entry 	$entry 		Entry entity
		 * @param  String 	$comment 	Comment content
		 * @param  User 	$user 		User entity
		 * @param  Integer	$replyTo 	Parent comment
		 * @return Integer 	[type]		Comment entity ID
		 */
		public function post(Entry $entry, String $comment, $user = null, $replyTo = null) : Int
		{
			try
			{
				// sanitize comment
				$comment = ( new CommentSanitizer($comment) )->getContent();

				// create a new comment entity
				$commentEntity = new Comment();
				$commentEntity->setEntry($entry);
				$commentEntity->setUser($user);
				$commentEntity->setContent($comment);

				if($replyTo != null)
				{
					// set parent
					$parentCommentEntity = $this->em->getRepository(Comment::class)->findOneBy(['id' => $replyTo]);

					if($parentCommentEntity)
					{
						$commentEntity->setReply($parentCommentEntity);
					}

					// set root value
					$root = $this->getCommentRoot($commentEntity);

					if($root)
					{
						$commentEntity->setRoot($root);
					}
					else if( ! $root && $parentCommentEntity)
					{
						$commentEntity->setRoot($parentCommentEntity);
					}
				}

				$this->em->persist($commentEntity);
				$this->em->flush();

				// increase comment count
				$this->updateCommentsCount($entry, $replyTo, "+");

				// refresh entry detail page cache
				$this->pageCacheService->refreshPageCache($entry->getSlug());

				// success message
				if($replyTo == null)
				{
					$successMssg = $this->translator->trans('entries_comments.comment_posted_successfully');
					$this->session->getFlashBag()->add('comment_success', $successMssg);
				}
				else
				{
					$successMssg = $this->translator->trans('entries_comments.reply_posted_successfully');
					$this->session->getFlashBag()->add('post_reply_success', $successMssg);
				}

				return $commentEntity->getId();
			}
			catch(\Exception $e)
			{
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
		 * @param  Comment 	$comment 	Comment entity
		 */
		public function remove(Comment $comment) : Bool
		{
			try
			{
				$entry = $comment->getEntry();

				// remove
				$this->em->remove($comment);
				$this->em->flush();

				// decrease comment count
				$this->updateCommentsCount($entry, false, "-");

				// refresh entry detail page cache
				$this->pageCacheService->refreshPageCache($entry->getSlug());

				// success message
				$successMssg = $this->translator->trans('entries_comments.comment_removed_successfully');
				$this->session->getFlashBag()->add('remove_comment_success', $successMssg);

				return true;
			}
			catch(\Exception $e)
			{
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
		 */
		public function update(Comment $comment, String $content)
		{
			try
			{	
				$comment->setContent($content);

				// save
				$this->em->persist($comment);
				$this->em->flush();

				// success message
				$successMssg = $this->translator->trans('entries_comments.comment_updated_successfully');
				$this->session->getFlashBag()->add('comment_success', $successMssg);

				// refresh cache
				$entry = $comment->getEntry();
				$this->pageCacheService->refreshPageCache($entry->getSlug());

				return $comment;
			}
			catch(\Exception $e)
			{
				// log error
				$this->logger->logException($e);
			}
		}

		/** 
		 * Increase 'totalComments' count for Entry entity
		 *
		 * @param  Entry 	$entry 		Entry entity
		 * @return 
		 */
		public function updateCommentsCount(Entry $entry, Bool $isReply = false, String $operator="+")
		{
			$updateEntryCommentsCount = new UpdateEntryCommentsCount($this->em, $entry, $this->logger);

			if($operator == "+")
			{
				$updateEntryCommentsCount->increase($isReply);
			}
			else if($operator == "-")
			{
				$updateEntryCommentsCount->decrease($isReply);
			}
		}

		/**
		 * Get comment root value
		 *
		 * @param  Comment 			$comment 	Comment entity
		 * @return Comment|null 	[type] 		Root comment entity
		 */
		public function getCommentRoot(Comment $comment)
		{
			if( empty($comment->getReply()) )
			{
				return $comment;
			}
			else
			{
				$reply = $comment->getReply();
				return $reply->getRoot();
			}
		}
	}