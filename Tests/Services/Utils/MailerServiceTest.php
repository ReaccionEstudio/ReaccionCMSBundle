<?php
	
	namespace App\ReaccionEstudio\ReaccionCMSBundle\Tests\Services\Utils;
	
	use PHPUnit\Framework\TestCase;
	use Doctrine\Common\Collections\ArrayCollection;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate;

	/**
	 * Mailer service test
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class MailerServiceTest extends KernelTestCase
	{
		private $mailerService;
		private $em;

		public function setUp()
	    {
	    	$kernel = self::bootKernel();
	    	$this->mailerService = $kernel->getContainer()->get('reaccion_cms.mailer');
	    	$this->em = $kernel->getContainer()->get('doctrine')->getManager();
	    }

	    public function testSendTemplate()
	    {
	    	$success = 0;
	    	$adminEmails = $this->em->getRepository(User::class)->getAdminEmailAdresses();

	    	foreach($adminEmails as $adminEmail)
	    	{
	    		$result = $this->mailerService->sendTemplate("test-email", [ $adminEmail['email'] ]);
	    		if($result == true) $success++;
	    	}

	    	$this->assertGreaterThan(0, $success);
	    }
	}