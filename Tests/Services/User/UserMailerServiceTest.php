<?php
	
	namespace ReaccionEstudio\ReaccionCMSBundle\Tests\Services\User;
	
	use PHPUnit\Framework\TestCase;
	use Doctrine\Common\Collections\ArrayCollection;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

	/**
	 * User mailer service test
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class UserMailerServiceTest extends KernelTestCase
	{
		private $userMailerService;
		private $admins;
		private $em;

		public function setUp()
	    {
	    	$kernel = self::bootKernel(); 
	    	$this->em = $kernel->getContainer()->get('doctrine')->getManager();
	    	$this->userMailerService = $kernel->getContainer()->get('reaccion_cms.user_mailer');
	    	$this->admins = $this->em->getRepository(User::class)->getAdminEntities();
	    }

	    public function testSendConfirmationEmailMessage()
	    {
	    	$totalAdmins = count($this->admins);
	    	$totalSuccessMssgs = 0;

	    	foreach($this->admins as $userEntity)
	    	{
	    		$result = $this->userMailerService->sendConfirmationEmailMessage($userEntity);
	    		if($result) $totalSuccessMssgs++;
	    	}
			
	    	$this->assertEquals($totalAdmins, $totalSuccessMssgs);
	    }

	    public function testSendResettingEmailMessage()
	    {
	    	$totalAdmins = count($this->admins);
	    	$totalSuccessMssgs = 0;

	    	foreach($this->admins as $userEntity)
	    	{
	    		$result = $this->userMailerService->sendResettingEmailMessage($userEntity);
	    		if($result) $totalSuccessMssgs++;
	    	}
			
	    	$this->assertEquals($totalAdmins, $totalSuccessMssgs);
	    }
	}