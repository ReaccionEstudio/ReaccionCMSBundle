<?php
	
	namespace ReaccionEstudio\ReaccionCMSBundle\Tests\Services\Email;
	
	use PHPUnit\Framework\TestCase;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate;

	/**
	 * Email template service test
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class EmailTemplateServiceTest extends KernelTestCase
	{
		private $emailTemplateService;

		public function setUp()
	    {
	    	$kernel = self::bootKernel();
	    	$this->emailTemplateService = $kernel->getContainer()->get('reaccion_cms.email_template');
	    }

	    public function testLoadTemplate()
	    {
	    	$this->emailTemplateService->loadTemplate('test-email');
	    	$bodyHtml = $this->emailTemplateService->getBodyHtml();

	    	$this->assertNotEmpty($bodyHtml);
	    }
	}