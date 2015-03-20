<?php

namespace Codeception\Module;

use Cakeception\Controller;

class Cakeception
{
	protected $controller;

	public function __construct()
	{
		$this->controller = new Controller;
	}

	public function controller()
	{
		return $this->controller;
	}	
}