<?php

/**
 * CakeCeption
 * --
 * An integration tool for CakePHP v2.* to Codeception
 *
 * @author @rupaheizu <rupaheizu@hkz.io>
 * @copyright @rupaheizu, HkzPjt, 2015
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
