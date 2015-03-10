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

App::uses('ClassRegistry', 'Utility');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Router', 'Routing');

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
	 * @param string $url
	 * @return $this CakeCeption
	 */
	public function request($url)
	{
		$this->controllerName = $this->parseController($url);
		$this->controllerAction = $this->parseControllerAction($url);

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
	 * Apply headers to the request
	 *
	 * @param array $headers
	 * @return $this CakeCeption
	 */
	public function headers($headers)
	{
		foreach($headers as $method => $value) {
			// since we're running from cgi
			// there will be no traces of any http headers in the $_SERVER var
			// we need to write them manually
			$this->writeServerVars($method, $value);
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
		$params = array_key_exists('params', $params) && count($params['params']) > 0 ? $params['params'] : [];
		array_merge($this->request->params, $params);

		$data = array_key_exists('data', $params) && count($params['data']) > 0 ? $params['data'] : [];
		$this->request->query = [
			'data' => $data
		];

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
		return ucfirst(explode('/', $string)[0]) . 'Controller';
	}

	/**
	 * Parses the controller
	 *
	 * @param string
	 * @return string
	 */
	protected function parseControllerAction($string)
	{
		$string = explode('/', $string);

		return ! isset($string[1]) ? 'index' : $string[1];
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
