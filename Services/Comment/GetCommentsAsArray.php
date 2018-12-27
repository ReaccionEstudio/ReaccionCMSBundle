<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;

	/**
	 * Convert comments list as array
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class GetCommentsAsArray
	{
		/**
		 * @var Array
		 *
		 * Comments list as array
		 */
		private $comments = [];

		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManagerInterface
		 */
		private $em;

		/**
		 * @var Entry
		 *
		 * Entry entity
		 */
		private $entry;

		/**
		 * @var Integer
		 *
		 * Page
		 */
		private $page;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Entry $entry, Int $page = 1)
		{
			$this->em 	 = $em;
			$this->entry = $entry;
			$this->page  = $page;
		}

		public function getComments()
		{
			$rawComments = $this->getCommentsFromDatabase();

			var_dump($rawComments);

			return $this->comments;	
		}

		private function getCommentsFromDatabase()
		{
			$dql =  "
					SELECT 
					c.content, 
					c.createdAt,
					c.updatedAt
					FROM  App\ReaccionEstudio\ReaccionCMSBundle\Entity\Comment c 
					WHERE c.entry = :entry
					";

			/*
			LEFT JOIN App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent p 
			WITH p.id = m.parent 
			*/

			$query = $this->em
						  ->createQuery($dql)
						  ->setParameter("entry", $this->entry);

			return $query->getArrayResult();
		}
	}