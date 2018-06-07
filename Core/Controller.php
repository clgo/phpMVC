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


	/**
	 * Call method to access private or non available method, using this
	 * a before and after method can be call before accessing the actual
	 * method to be call.
	 * 
	 * @param array $route_params	Parameters from the route
	 *
	 * @return void
	 */
	public function __call($name, $args)
	{
		// Append Action to the controller method name, 
		$method = $name . 'Action';

		if (method_exists($this, $method)) {
			if ($this->before() !== false) {
				call_user_func_array([$this, $method], $args);
				$this->after();
			}
		} else {
			echo "Method $method not found in controller " . get_class($this);
		}
	}

	/**
	 * Before filter - called before an action method.
	 *
	 * @return void
	 */
	protected function before()
	{

	}

	/**
	 * After filter - called after an action method.
	 *
	 * @return void
	 */
	protected function after()
	{
		
	}
}