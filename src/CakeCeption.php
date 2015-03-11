<?php

/**
 * CakeCeption
 * --
 * An integration tool for CakePHP v2.* to Codeception
 *
 * @author @rupaheizu <rupaheizu@hkz.io>
 * @copyright @rupaheizu, HkzPjt, 2015
 * @license BSD 3-Clause
 * @package CakeCeption
 */

App::uses('Router', 'Routing');
App::uses('CakeRequest', 'Network');

class CakeCeption {

	/**
	 * CakeRequest container
	 *
	 * @var \CakeRequest;
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
		$this->request = new CakeRequest;
		$this->appController = new AppController;
	}

	/**
	 * Sends a request to a given {controller}/{action}
	 *
	 * @param string $pointer
	 * @return $this CakeCeption
	 */
	public function request($pointer)
	{
		$this->controllerName = $this->parseController($pointer);
		$this->controllerAction = $this->parseControllerAction($pointer);

		$this->request->params = [
			'controller' => $this->controllerName,
			'action' => $this->controllerAction,
			'pass' => [],
			'named' => []
		];

		App::uses($this->controllerName, 'Controller');
		$this->controller = new $this->controllerName($this->request);

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
	 * Apply parameters to the request
	 *
	 * @param array $params
	 * @return $this CakeCeption
	 */
	public function params($params)
	{
		$innerParams = array_key_exists('params', $params) && count($params['params']) > 0 ? $params['params'] : [];
		$this->request->params = array_merge($this->request->params, $innerParams);

		$data = array_key_exists('data', $params) && count($params['data']) > 0 ? $params['data'] : []; // POST DATA
		$queries = array_key_exists('queries', $params) && count($params['queries']) > 0 ? $params['queries'] : []; // GET DATA
		$this->request->query = [
			'data' => $data
		];

		if ( count($queries) > 0 ) {
			$this->request->query = array_merge($this->request->query, $queries);
		}

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
	 * Parses the controller
	 *
	 * @param string
	 * @return string
	 */
	protected function parseController($string)
	{
		return explode('@', $string)[0] . 'Controller';
	}

	/**
	 * Parses the controller
	 *
	 * @param string
	 * @return string
	 */
	protected function parseControllerAction($string)
	{
		$string = explode('@', $string);

		return ! isset($string[1]) ? 'index' : $string[1];
	}

	/**
	 * Writes _COOKIE variables
	 *
	 * @param string $key
	 * @param string $value
	 */
	protected function writeCookieVars($key, $value)
	{
		$_COOKIE[$key] = $value;
	}

	/**
	 * Writes _SERVER variables
	 *
	 * @param string $key
	 * @param string $value
	 */
	protected function writeServerVars($key, $value)
	{
		$_SERVER[$key] = $value;
	}

}
