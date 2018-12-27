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
		 * @var Integer
		 *
		 * Entry ID
		 */
		private $entryId;

		/**
		 * @var Integer
		 *
		 * Page
		 */
		private $page;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Int $entryId, Int $page = 1)
		{
			$this->em 	 	= $em;
			$this->page  	= $page;
			$this->entryId 	= $entryId;

			$this->getNestedComments();
		}

		/**
		 * Get comments var value
		 *
		 * @return Array  $this->comments  Entry comments
		 */
		public function getComments() : Array
		{
			return $this->comments;	
		}

		/**
		 * Get nested comments array
		 */
		private function getNestedComments() : void
		{
			$comments = array();
			$nested   = array();

			// get raw comments
			$rawComments = $this->getCommentsFromDatabase();

			// store comments where array key is the entry id
			foreach($rawComments as $comment)
			{
				$id = $comment['id'];
				$comments[$id] = $comment;
			}

			// build nested comments array
			foreach ( $comments as &$s ) 
			{
				if ( is_null($s['parent_id']) ) 
				{
					// no parent_id so we put it in the root of the array
					$nested[] = &$s;
				}
				else 
				{
					$pid = $s['parent_id'];
					
					if ( isset($comments[$pid]) ) 
					{
						if ( ! isset($comments[$pid]['children']) ) 
						{
							$comments[$pid]['children'] = array();
						}

						$comments[$pid]['children'][] = &$s;
					}
				}
			}

			$this->comments = $nested;
		}

		/**
		 * Get comments from database (DQL query)
		 *
		 * @return Array  [type]  Query result
		 */
		// TODO: add pagination
		private function getCommentsFromDatabase() : Array
		{
			$dql =  "
					SELECT 
					c.id,
					c.content, 
					c.createdAt,
					c.updatedAt,
					u.username AS creatorUsername,
					u.email AS creatorEmail,
					u.nickname AS creatorNickname,
					cp.id AS parent_id
					FROM  App\ReaccionEstudio\ReaccionCMSBundle\Entity\Comment c 
					LEFT JOIN App\ReaccionEstudio\ReaccionCMSBundle\Entity\User u 
					WITH c.user = u.id
					LEFT JOIN App\ReaccionEstudio\ReaccionCMSBundle\Entity\Comment cp 
					WITH c.reply = cp.id 
					WHERE c.entry = :entry
					ORDER BY c.id ASC
					";

			$query = $this->em
						  ->createQuery($dql)
						  ->setParameter("entry", $this->entryId);

			return $query->getArrayResult();
		}
	}