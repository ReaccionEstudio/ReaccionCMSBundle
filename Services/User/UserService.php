<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Services\User;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Session\Session;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\User;
	use Symfony\Component\Translation\TranslatorInterface;

	class UserService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManagerInterface
		 */
		private $em;

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
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, Session $session)
		{
			$this->em = $em;
			$this->session = $session;
			$this->translator = $translator;
		}

		/**
		 * Check if username and email is available
		 * 
		 * @param 	String 	$username 	Username
		 * @param 	String 	$email 		Email
		 * @return 	Boolean true|false  Signup process result
		 */
		public function createNewUserIfAvailable(String $username, String $email, String $password)
		{
			$userExists = $this->em->getRepository(User::class)->userExists($username, $email, $password);

			if($userExists['total'] > 0)
			{
				if($username == $userExists['usernameCanonical'])
				{
					$this->session->getFlashBag()->add('signup_errors', $this->translator->trans('signup.username_exists'));
				}

				if($email == $userExists['emailCanonical'])
				{
					$this->session->getFlashBag()->add('signup_errors', $this->translator->trans('signup.email_exists'));
				}

				return false;
			}
			else if($userExists['total'] == 0)
			{
				$this->createNewUser($username, $email, $password);
				return true;
			}

			return false;
		}

		/**
		 * Create new user
		 *
		 * @param  String 	$username 	Username
		 * @param  String 	$email 		Email address
		 * @param  String 	$password	Password
		 * @return User 	$user 		New user entity
		 */
		public function createNewUser(String $username, String $email, String $password)
		{
			try
			{
				$user = new User();
				$user->setUsername($username);
				$user->setEmail($email);
				$user->setPlainPassword($password);
				$user->setEnabled(true);

				$this->em->persist($user);
				$this->em->flush();
			}
			catch(\Exception $e)
			{
				// TODO: log error
				$this->session->getFlashBag()->add('signup_errors', $e->getMessage());
			}

			return $user;
		}
	}