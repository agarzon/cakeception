<?php

use Cakeception\Application;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
	protected $app;

	public function __construct()
	{
		$this->app = new Application;
	}

	public function testInstance()
	{
		$this->assertTrue(($this->app instanceof Application));
	}
}