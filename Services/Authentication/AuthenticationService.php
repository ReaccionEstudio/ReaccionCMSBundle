<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Authentication;

	use Doctrine\ORM\EntityManagerInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
	use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
    use Symfony\Contracts\Translation\TranslatorInterface;

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
		 * @var SessionInterface
		 *
		 * Session
		 */
		private $session;

		/**
		 * @var TokenStorageInterface
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
		public function __construct(SessionInterface $session, TranslatorInterface $translator, TokenStorageInterface $tokenStorage)
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

			$this->createUserToken($this->user);

			if($flashMessage)
			{
				$this->session->getFlashBag()->add('signin_success', $this->translator->trans('signin.signin_success', ['%username%' => $this->user->getUsername() ]));
			}
		}

		/**
		 * Create user token and save it in session
		 *
		 * @param 	User 	$user 	User entity
		 */
		public function createUserToken(User $user)
		{
			$token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
			$this->tokenStorage->setToken($token);

			// set user language as token attribute
			$token->setAttributes([ 'userLanguage' => $user->getLanguage() ]);

			$this->session->set('_security_main', serialize($token));
			$this->session->save();
		}
	}
