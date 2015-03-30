<?php

namespace Cakeception;

use Cakeception\Application;

class Controller
{

	/**
	 * App container
	 *
	 * @var Cakeception\Application
	 * @access protected
	 */
	protected $app;

	/**
	 * CakeRequest container
	 *
	 * @var \CakeRequest
	 * @access protected
	 */
	protected $request;

	/**
	 * Controller container
	 *
	 * @var \{controllerName}Controller
	 */
	protected $controller;

	/**
	 * Controller Name
	 *
	 * @var string
	 * @access protected
	 */
	protected $controllerName;

	/**
	 * Controller Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $controllerAction;

	/**
	 * Initialize the class
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->app = new Application;
	}

	/**
	 * Instantiate a controller
	 *
	 * @param string $controller
	 * @return $this CakeCeption
	 */
	public function init($controller)
	{
		$this->give('Controller');

		$this->controllerName = explode('Controller', $controller)[0];
		$this->controller = new $controller;

		return $this;
	}

	/**
	 * Forges the mock request
	 *
	 * @param string $controller
	 * @param string $action
	 * @return Object CakeRequest
	 */
	protected function forgeRequest($controller, $action)
	{
		// $this->request = $this->app->give('CakeRequest');

		$this->request->params = [ // this should be mocked instead
			'controller' => $this->controllerName,
			'action' => $action,
			'pass' => [],
			'named' => []
		];

		return $this->request;
	}

	/**
	 * The action to call form the controller
	 *
	 * @param string $action
	 * @return $this CakeCeption
	 */
	public function call($action)
	{
		$this->controller = new $this->controller(
			$this->forgeRequest($this->controllerName, $action)
		);

		return $this;
	}

	/**
	 * Apply cookies to the request
	 * --
	 * There will be no traces of the $_COOKIE var in CGI
	 *
	 * @param array $cookies
	 * @return $this CakeCeption
	 */
	public function cookies($cookies)
	{
		foreach($cookies as $cookie => $value)
		{
			$this->writeCookieVars($cookie, $value);
		}

		return $this;
	}

	/**
	 * Apply headers to the request
	 * --
	 * There will be no traces of HTTP related $_SERVER vars in CGI
	 *
	 * @param array $headers
	 * @return $this CakeCeption
	 */
	public function headers($headers)
	{
		foreach($headers as $method => $value) {
			$this->writeServerVars($method, $value);
		}

		return $this;
	}

	/**
	 * Run the before filter of the current controller
	 *
	 * @return $this CakeCeption
	 */
	public function beforeFilter()
	{
		if ( method_exists($this->controller, 'beforeFilter') ) {
			$this->controller->beforeFilter();
		}

		return $this;
	}

	/**
	 * Mock Component and Model classes of the current controller
	 *
	 * @return void
	 */
	public function constructControllerClasses()
	{
		$properties = ['uses', 'components'];

		foreach ($properties as $property) {
			if ( property_exists($this->controller, 'uses') ) {
				// do here
			}
		}
	}
	
	/**
	 * Apply queries to the request
	 * 
	 * @param array $queries
	 * @return $this CakeCeption
	 */
	public function queries(array $queries)
	{
		$this->request->query = array_merge($this->request->query, $queries);
		
		return $this;
	}
	
	/**
	 * Apply data to the request
	 * 
	 * @param array $data
	 * @return $this CakeCeption
	 */
	public function data(array $data)
	{
		$this->request->query = array_merge($this->request->query, $data);
		
		return $this;
	}

	/**
	 * Apply parameters to the request
	 *
	 * @param array $params
	 * @return $this CakeCeption
	 */
	public function params(array $params)
	{
		$this->request->params = array_merge($this->request->params, $params);

		return $this;
	}

	/**
	 * Apply on-the-fly properties to the controller
	 *
	 * @param array $properties
	 */
	public function properties($properties)
	{
		foreach($properties as $property => $value) {
			$this->controller->{$property} = $value;
		}

		return $this;
	}

	/**
	 * Executes the controller action
	 *
	 * @return array
	 */
	public function execute()
	{
		$this->controller->constructClasses();
		$this->controller->invokeAction($this->request);

		return $this->controller;
	}

	/**
	 * Parses the redirect
	 *
	 * @param string $url
	 * @return string
	 */
	public static function parseRedirect($url)
	{
		return $url;
	}

}
