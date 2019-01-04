<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\Authentication;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Session\Session;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
	use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

	/**
	 * Authentication service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class AuthenticationService
	{
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
		 * @var TokenStorage
		 *
		 * TokenStorage
		 */
		private $tokenStorage;

		/**
		 * @var User
		 *
		 * User entity
		 */
		private $user = null;

		/**
		 * Constructor
		 */
		public function __construct(Session $session, TranslatorInterface $translator, TokenStorage $tokenStorage)
		{
			$this->session 		= $session;
			$this->translator 	= $translator;
			$this->tokenStorage = $tokenStorage;
		}

		/**
		 * Set user entity
		 *
		 * @param 	User 					$user 	User entity
		 * @return 	AuthenticationService	$this 	AuthenticationService class instance
		 */
		public function setUser(User $user)
		{
			$this->user = $user;
			return $this;
		}

		/**
		 * Authenticates a user in the system
		 *
		 * @param  Boolean 	$flashMessage 	Indicate if flash message has to be generated
		 * @return void 	[type]
		 */
		public function authenticate(Bool $flashMessage = false) : void
		{
			if($this->user == null) return;

			$token = new UsernamePasswordToken($this->user, null, 'main', $this->user->getRoles());
			$this->tokenStorage->setToken($token);

			// set user language as token attribute
			$token->setAttributes([ 'userLanguage' => $this->user->getLanguage() ]);

			$this->session->set('_security_main', serialize($token));
			$this->session->save();

			if($flashMessage)
			{
				$this->session->getFlashBag()->add('signin_success', $this->translator->trans('signin.signin_success', ['%username%' => $this->user->getUsername() ]));
			}
		}
	}