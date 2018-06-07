<?php

namespace App\Controllers\Admin;

/**
 * User admin controller
 *
 * PHP version 7.1
 */
class Users extends \Core\Controllers
{

	/**
	 * Before filter
	 *
	 * @return void
	 */
	protected function before()
	{

		// Make sure an admin user is logged in for example
		// return false;
	}

	/**
	 * Show the index page
	 *
	 * @return void
	 */
	public function indexAction()
	{
		echo 'User admin index';
	}

}