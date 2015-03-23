<?php

namespace Codeception\Module;

use Cakeception\Controller;
use Codeception\Module;

class Cakeception extends Module
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
