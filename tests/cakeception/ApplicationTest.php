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
		$this->assertInstanceOf('Cakeception\Application', $this->app);
	}

	public function testOptionsProperty()
	{
		$this->app = new Application([
			'test' => true
		]);

		$this->assertTrue($this->app->getOptions()['test']);
	}
}