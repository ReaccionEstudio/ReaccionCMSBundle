<?php
	
	namespace App\ReaccionEstudio\ReaccionCMSBundle\Tests\Services\Utils;
	
	use PHPUnit\Framework\TestCase;
	use Doctrine\Common\Collections\ArrayCollection;
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

		public function setUp()
	    {
	    	$kernel = self::bootKernel();
	    	$this->mailerService = $kernel->getContainer()->get('reaccion_cms.mailer');
	    }

	    public function testSendTemplate()
	    {
	    	$result = $this->mailerService->sendTemplate("test-email", ["alberto@reaccionestudio.com"]);
	    	$this->assertTrue($result);
	    }
	}