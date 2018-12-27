<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment;
	
	use Doctrine\ORM\EntityManagerInterface;
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
		 * @var User
		 *
		 * User entity
		 */
		private $user = null;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Session $session, TranslatorInterface $translator)
		{
			$this->em 			= $em;
			$this->session 		= $session;
			$this->translator 	= $translator;
		}

		/**
		 * Get comments list
		 */
		public function getComments(Int $entryId) : Array
		{
			$page = 1; // TODO: get from query parameter
			$getCommentsAsArray = new GetCommentsAsArray($this->em, $entryId, $page);
			return $getCommentsAsArray->getComments();
		}
	}