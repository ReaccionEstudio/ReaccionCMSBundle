<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment;
	
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment\GetCommentsAsArray;

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
		 * @var User
		 *
		 * User entity
		 */
		private $user = null;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Session $session, TranslatorInterface $translator, RequestStack $request)
		{
			$this->em 			= $em;
			$this->session 		= $session;
			$this->translator 	= $translator;
			$this->request 		= $request->getCurrentRequest();
		}

		/**
		 * Get comments list
		 */
		public function getComments(Int $entryId) : Array
		{
			$page = ($this->request->query->get('cp')) ?? 1;
			$getCommentsAsArray = new GetCommentsAsArray($this->em, $entryId, $page);
			return $getCommentsAsArray->getComments();
		}
	}