<?php
	
	namespace App\ReaccionEstudio\ReaccionCMSBundle\Tests\Services\Utils;
	
	use PHPUnit\Framework\TestCase;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

	/**
	 * Encryptor test
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class EncryptorTest extends KernelTestCase
	{
		private $encryptor;

		public function setUp()
	    {
	    	$kernel = self::bootKernel();
	    	$this->encryptor = $kernel->getContainer()->get('reaccion_cms.encryptor');
	    	$this->encryptionPassword = "encryption_password_123";
	    }

	    public function testEncrypt()
	    {
	    	$result = $this->encryptor->encrypt("test string", $this->encryptionPassword);

	    	$this->assertNotEmpty($result);
	    }

	    public function testDecrypt()
	    {
	    	$encryptedValue = 'def50200ca2417891ec01d63417b750ba3bcb1b53997d7029d70aed625bae9536f06e791a3eba4144380cb370414b1d29e981ec2af5fa7acb7f20f68a2bf2e7ca43e4a80377997b3022d0917020cec8c40c4c471a60a8be1641ef2c4a9b516';

	    	$result = $this->encryptor->decrypt($encryptedValue, $this->encryptionPassword);

	    	$this->assertNotEmpty($result);
	    	$this->assertEquals($result, "test string");
	    }
	}