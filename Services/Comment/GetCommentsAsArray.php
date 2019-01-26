<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Comment;

	use Doctrine\ORM\EntityManagerInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigServiceInterface;

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
		 * @var ConfigServiceInterface
		 *
		 * Config service
		 */
		private $config;

		/**
		 * @var Integer
		 *
		 * Page limit
		 */
		private $limit;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Int $entryId, Int $page = 1, ConfigServiceInterface $config)
		{
			$this->em 	 	= $em;
			$this->page  	= $page;
			$this->entryId 	= $entryId;
			$this->config  	= $config;
			$this->limit  	= $this->config->get("entries_comments_pagination_limit");

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
			$rawComments = $this->getRawComments();

			// store comments where array key is the entry id
			foreach($rawComments as $comment)
			{
				$id = $comment['id'];
				$comments[$id] = $comment;
			}

			// build nested comments array
			foreach ( $comments as &$s ) 
			{
				if ( ! isset($s['parent_id']) ) 
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
		 * Get raw comments array with comments and their replies
		 *
		 * @return Array 	$rawComments 	Raw comments array
		 */
		private function getRawComments() : Array
		{
			$rawComments = $this->getCommentsFromDatabase();
			$replies 	 = $this->getRepliesFromDatabase($rawComments);

			if( ! empty($replies))
			{
				foreach($replies as $reply)
				{
					$rawComments[] = $reply;
				}
			}

			return $rawComments;
		}

		/**
		 * Get comments from database (DQL query)
		 *
		 * @return Array  [type]  Query result
		 */
		private function getCommentsFromDatabase() : Array
		{
			$dql =  "
					SELECT 
					c.id,
					c.content, 
					c.createdAt,
					c.updatedAt,
					u.username AS creatorUsername,
					u.id AS creatorId,
					u.email AS creatorEmail,
					u.nickname AS creatorNickname
					FROM  ReaccionEstudio\ReaccionCMSBundle\Entity\Comment c 
					LEFT JOIN ReaccionEstudio\ReaccionCMSBundle\Entity\User u 
					WITH c.user = u.id
					WHERE c.entry = :entry 
					AND c.reply IS NULL
					ORDER BY c.id ASC
					";

			$query = $this->em
						  ->createQuery($dql)
						  ->setParameter("entry", $this->entryId)
						  ->setMaxResults($this->limit)
        				  ->setFirstResult($this->getQueryOffset());

			return $query->getArrayResult();
		}

		/**
		 * Get all comments replies from database
		 *
		 * @param  Array 	$rawComments 	Raw comments array
		 * @return Array 	[type] 			Comments replies array
		 */
		private function getRepliesFromDatabase(Array $rawComments) : Array
		{
			$rootValues = [];

			foreach($rawComments as $comment)
			{
				$rootValues[] = $comment['id'];
			}

			$dql =  "
					SELECT 
					c.id,
					c.content, 
					c.createdAt,
					c.updatedAt,
					IDENTITY(c.root), 
					u.username AS creatorUsername,
					u.id AS creatorId,
					u.email AS creatorEmail,
					u.nickname AS creatorNickname, 
					cp.id AS parent_id
					FROM  ReaccionEstudio\ReaccionCMSBundle\Entity\Comment c 
					LEFT JOIN ReaccionEstudio\ReaccionCMSBundle\Entity\User u 
					WITH c.user = u.id 
					LEFT JOIN ReaccionEstudio\ReaccionCMSBundle\Entity\Comment cp 
					WITH c.reply = cp.id 
					WHERE c.entry = :entry 
					AND c.root IN (:rootValues)
					ORDER BY c.id ASC
					";

			$query = $this->em
						  ->createQuery($dql)
						  ->setParameter("entry", $this->entryId)
						  ->setParameter("rootValues", $rootValues);

			return $query->getArrayResult();
		}

		/**
		 * Get query offset value
		 *
		 * @return Integer [type] Offset value
		 */
		private function getQueryOffset() : Int
		{
			return $this->limit * ($this->page - 1);
		}
	}