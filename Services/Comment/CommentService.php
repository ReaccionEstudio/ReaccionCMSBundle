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
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Session $session, TranslatorInterface $translator, RequestStack $request, ConfigService $config, PageCacheService $pageCacheService)
		{
			$this->em 				= $em;
			$this->session 			= $session;
			$this->translator 		= $translator;
			$this->config 			= $config;
			$this->request 			= $request->getCurrentRequest();
			$this->pageCacheService	= $pageCacheService;
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
		public function postComment(Entry $entry, String $comment, $user = null, $replyTo = null) : Int
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
					$commentEntity->setReply($replyTo);
				}

				$this->em->persist($commentEntity);
				$this->em->flush();

				// increase comment count
				$this->updateCommentsCount($entry, $replyTo, "+");

				// refresh entry detail page cache
				$this->pageCacheService->refreshPageCache($entry->getSlug());

				// success message
				$successMssg = $this->translator->trans('entries_comments.comment_posted_successfully');
				$this->session->getFlashBag()->add('comment_success', $successMssg);

				return $commentEntity->getId();
			}
			catch(\Exception $e)
			{
				// TODO: log error
				// error message
				return 0;
			}
		}

		/**
		 * Remove comment
		 *
		 * @param  Comment 	$comment 	Comment entity
		 */
		public function removeComment(Comment $comment) : Bool
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
				$this->session->getFlashBag()->add('comment_success', $successMssg);

				return true;
			}
			catch(\Exception $e)
			{
				// TODO: log error
				return false;
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
			$updateEntryCommentsCount = new UpdateEntryCommentsCount($this->em, $entry);

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
		 * Update 'totalComments' field for Entry entity (Executed by CRON)
		 *
		 * @return void
		 */
		public function updateEntryComments() : void
		{
			// TODO ...
		}
	}