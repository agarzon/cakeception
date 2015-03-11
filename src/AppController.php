<?php

/**
 * CakeCeption
 * --
 * An integration tool for CakePHP v2.* to Codeception
 *
 * @author @rupaheizu <rupaheizu@hkz.io>
 * @copyright @rupaheizu, HkzPjt, 2015
 * @license BSD 3-Clause
 * @package AppController Nullify
 */

App::uses('Controller', 'Controller');

class AppController extends Controller {

	/**
	 * Overwrite rendering function in case the project has $this->render in its controllers
	 *
	 * @param string $view [nullify]
	 * @param string $layout [nullify]
	 * @return void
	 */
	public function render($view = null, $layout = null) // nulify rendering
	{

	}

	/**
	 * Overwrite redirecting function in case the project has $this->redirect in its controllers
	 *
	 * @param string|array $url // returns [Controller@action] to test
	 * @param integer $status [nullify]
	 * @param boolean $exit [nullify]
	 */
	public function redirect($url, $status = null, $exit = true)
	{
		return $url;
	}
}