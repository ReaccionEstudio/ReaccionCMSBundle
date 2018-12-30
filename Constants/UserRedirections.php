<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Constants;

	/**
	 * User redirection constants
     *
     * @author Alberto Vian <alberto@reaccionestudio.com>
     */
	class UserRedirections
	{
		const REDIRECTIONS_BY_EVENTS = [

			'login_route_user_already_logged' => [
				'type' => 'referrer'
			],
			'user_login_successful' => [
				'type' => 'referrer'
			],

		];
	}