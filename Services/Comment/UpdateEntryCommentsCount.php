<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\LoggerService;

	/**
	 * Update totalComments field value for a entry entity
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class UpdateEntryCommentsCount
	{
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
		 * @var LoggerService
		 *
		 * Logger service
		 */
		private $logger;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Entry $entry, LoggerService $logger)
		{
			$this->em 	  = $em;
			$this->entry  = $entry;
			$this->logger = $logger;
		}

		/**
		 * Increase comments count
		 *
		 * @param  Boolean 	$isReply 	Indicate if it is a comment reply
		 * @return void 	[type]
		 */
		public function increase(Bool $isReply = false) : void
		{
			$totalComments = $this->entry->getTotalComments();

			if($isReply)
			{
				$totalComments['replies'] = $totalComments['replies'] + 1;
			}
			else
			{
				$totalComments['comments'] = $totalComments['comments'] + 1;
			}

			$totalComments['total'] = $totalComments['total'] + 1;

			$this->setTotalComments($totalComments);
		}

		/**
		 * Decrease comments count
		 *
		 * @param  Boolean 	$isReply 	Indicate if it is a comment reply
		 * @return void 	[type]
		 */
		public function decrease(Bool $isReply = false) : void
		{
			$totalComments = $this->entry->getTotalComments();

			if($isReply)
			{
				$totalComments['replies'] = $totalComments['replies'] - 1;
				$totalComments['replies'] = ($totalComments['replies'] < 0) ? 0 : $totalComments['replies'];
			}
			else
			{
				$totalComments['comments'] = $totalComments['comments'] - 1;
				$totalComments['comments'] = ($totalComments['comments'] < 0) ? 0 : $totalComments['comments'];
			}

			$totalComments['total'] = $totalComments['total'] - 1;
			$totalComments['total'] = ($totalComments['total'] < 0) ? 0 : $totalComments['total'];

			$this->setTotalComments($totalComments);
		}

		/**
		 * Update entry entity 'totalComments' field value
		 *
		 * @param  Array 	$totalComments 		Array with comments data
		 * @return void 	[type]
		 */
		private function setTotalComments(Array $totalComments) : void
		{
			try
			{
				$this->entry->setTotalComments($totalComments);

				$this->em->persist($this->entry);
				$this->em->flush();
			}
			catch(\Exception $e)
			{
				// log error
				$this->logger->logException($e);
			}
		}
	}