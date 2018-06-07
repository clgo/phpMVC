<?php

namespace Core;

/**
 * Base Controller
 * 
 * PHP version 7.1
 */
abstract class Controller
{

	/**
	 * Parameters from the matched route
	 * @var array
	 */
	protected $route_params = [];

	/**
	 * Class constructor
	 *
	 * @param array $route_params 	Parameters from the route
	 *
	 * @return void
	 */

	/**
	 * Class constructor
	 * 
	 * @param array $route_params	Parameters from the route
	 *
	 * @return void
	 */
	public function __construct($route_params)
	{
		$this->route_params = $route_params;
	}
}