<?php

namespace App\Controllers;

/**
 * Home controller
 *
 * PHP version 7.1
 */
class Home extends \Core\Controller
{
	/**
	 * Show the index page
	 *
	 * @return void
	 */
	public function indexAction() {
		echo 'Hello from the index action in the Home controller!';
	}

	/**
	 * Before filter
	 *
	 * @return void
	 */
	protected function before()
	{
		echo "(before)   ";
		// If we return false, this can be use to check if the user has previously login before accessing
		// the actual method. It will be useful for things like authentication.
		// return false;
	}


	/**
	 * After filter
	 *
	 * @return void
	 */
	protected function after()
	{
		echo "   (after)";
		// return false;
	}

}
