<?php

namespace Core;
/**
 * Front controller
 *
 * PHP version 7.1
 */

class Router {
	/**
	 * Associative array of routes (the routing table)
	 * @var array
	 */
	protected $routes = [];

	/**
	 * Parameters from the matched route
	 * @var array
	 */
	protected $params = [];

	/**
	 * Add a route to the routing table
	 *
	 * @param string $route 	The route URL
	 * @param array  $params 	Parameters (controller, action, etc.)
	 *
	 * @return void
	 */
	public function add($route, $params = []) {
		// Convert the route to regular expression: escape forward slashes
		//echo "Convert the route to regular expression: escape forward slashes<BR />";
		//echo "=====================================================================<BR /><BR />";
		//echo "route: Before > $route<BR />";
		$route = preg_replace('/\//', '\\/', $route);
		//echo "route: After > $route<BR /><BR />";
		//echo "=====================================================================<BR /><BR /><BR />";


		// Convert variables e.g. {controller}
		//echo "Convert variables e.g. {controller}<BR />";
		//echo "=====================================================================<BR /><BR />";
		//echo "route: Before > $route<BR />";
		$route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
		//echo "route: After > $route<BR /><BR />";
		//echo "=====================================================================<BR /><BR /><BR />";


		// Convert variables with custom regular expressions e.g. {id:\d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

		// Add start and end delimiters, and case insenitive flag
		//echo "Add start and end delimiters, and case insenitive flag<BR />";
		//echo "=====================================================================<BR /><BR />";
		//echo "route: Before > $route<BR />";
		$route = '/^' . $route . '$/i';
		//echo "route: After > $route<BR /><BR />";
		//echo "*********************************************************************<BR /><BR /><BR />";


		$this->routes[$route] = $params;

	}

	/**
	 * Get all the routes from the routing table
	 *
	 * @return array
	 */
	public function getRoutes() {
		return $this->routes;
	}

	/**
	 * Match the route to the routes in the routing table, setting the $params
	 * property if a route is found.
	 *
 	 * @param string $url The route URL
	 *
	 * @return boolean	true if a match found, false otherwise
	 */

	public function match($url) {

		foreach ($this->routes as $route => $params) {
			if (preg_match($route, $url, $matches)) {
				// Get named capture group values
				//$params = [];

				foreach ($matches as $key => $match) {
					if (is_string($key)) {
						$params[$key] = $match;
					}
				}

				$this->params = $params;
				return true;
			}
		}
		return false;


			// echo "url = $url<BR />";
			// echo "'$route'=>'$params'<BR />";
			// if ($url == $route) {
			// 	$this->params = $params;
			// 	return true;
			// }			
		// }

		// Match to the fixed URL format /controller/action
		// $reg_exp = "/^(?P<controller>[a-z-+)\/(?P<action>[a-z-]+)$/";

		// if (preg_match($reg_exp, $url, $matches)) {
		// 	// Get named capture group values
		// 	$params = [];

		// 	foreach ($matches as $key => $match) {
		// 		# code...
		// 		if (is_string($key)) {
		// 			$params[$key] = $match;
		// 		}
		// 	}
		// }

		// $this->params = $params;
		// return true;
	}

	/**
	 * Get the currently matched parameters
	 *
	 * @return array
	 */

	public function getParams() {
		return $this->params;
	}


	/**
	 * Dispatch the route, create the objects and running the action method
	 * 
	 *
 	 * @param string $url The route URL
	 *
	 * @return void
	 */
	public function dispatch($url) {

		$url = $this->removeQueryStringVariables($url);

		if ($this->match($url)) {
			$controller = $this->params['controller'];
			// Naming convention of Controller Classes must follow the PSR-1 standard, 
			// Uppercase for all first letter of a word, likewise for class method in CamelCase
			$controller = $this->convertToStudlyCaps($controller);
			$controller = "App\Controllers\\$controller";

			// check if the controller class exists
			if (class_exists($controller)) {
				// dynamically creates the controller object
				$controller_object = new $controller($this->params);

				$action = $this->params['action'];
				$action = $this->convertToCamelCase($action);

				// check if the class contains the action method, and check if its public by using is_callable method. is_callablable takes two input, first the object of the class. Second the method name of the class.

				if (is_callable([$controller_object, $action])) {
					$controller_object->$action();
				} else {
					echo "Method $action (in controller $controller) not found";
				}
			} else {
				echo "Controller class $controller not found";
			}
		} else {
			echo "No route matched.";
		}
	}

	/**
	 * Convert the string with hyphens to StudlyCaps
	 * e.g. post-authors => PostAuthors
	 *
 	 * @param string $string The string to convert
	 *
	 * @return string
	 */
	protected function convertToStudlyCaps($string) {
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}


	/**
	 * Convert the string with hyphens to camelCase,
	 * e.g. add-new => addNew
	 *
 	 * @param string $string The string to convert
	 *
	 * @return string
	 */
	public function convertToCamelCase($string) {
		return lcfirst($this->convertToStudlyCaps($string));
	}


	/**
	 * Remove the query string variables from the URL (if any). As the full
	 * query string is used for the route, any variables at the end will need
	 * to be removed before the route is matched to the routing table. For
	 * example:
	 * URL 								$_SERVER['QUERY_STRING']	Route
	 * -----------------------------------------------------------------------------------------------
	 * localhost 						''							''
	 * localhost/?						''							''	 
	 * localhost/?page=1 				page=1						''
	 * localhost/posts?page=1			posts&page=1				posts/index
	 * localhost/posts/index 			posts/index 				posts/index
	 * localhost/posts/index?page=1		psts/index&page=1			posts/index
	 *
	 * A URL of the format localhost/?page (one variable name, no value) won't
	 * work however. (NB. The .htaccess file converts the first ? to a & when
	 * it's passed through to the $_SERVER variable).
	 *
 	 * @param string $url The full URL
	 *
	 * @return string The URL with the query string variables removed.
	 */
	protected function removeQueryStringVariables($url)
	{
		if ($url != '') {
			$parts = explode('&', $url, 2);

			if (strpos($parts[0], '=') === false) {
				$url = $parts[0];
			} else {
				$url = '';
			}
		}
		return $url;
	}

}