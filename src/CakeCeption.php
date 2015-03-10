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
	 * AppController container
	 *
	 * @var \AppController
	 */

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
	 * Sends a request to a given URL to be parsed by
	 *
	 * @param string $url
	 * @param array $headers [see $detecters in CakeRequest]
	 * @param array $params
	 * @return array
	 */
	public function requestToController($url, $headers, $params = [])
	{
		foreach($headers as $method => $value) {
			// since we're running from cgi
			// there will be no traces of any http headers in the $_SERVER var
			// we need to write them manually
			$this->writeServerVars($method, $value);
		}

		$controllerName = $this->parseController($url);
		$controllerAction = $this->parseControllerAction($url);

		$params = array_key_exists('params', $params) && count($params['params']) > 0 ? $params['params'] : [];
		$this->request->params = array_merge([
			'controller' => $controllerName,
			'action' => $controllerAction,
			'pass' => [],
			'named' => []
		], $params);

		$data = array_key_exists('data', $params) && count($params['data']) > 0 ? $params['data'] : [];
		$this->request->query = [
			'data' => $data
		];

		App::uses($controllerName, 'Controller');
		
		$controller = new $controllerName($this->request);
		$controller->constructClasses();
		$controller->$controllerAction();

		return $controller->viewVars;
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
