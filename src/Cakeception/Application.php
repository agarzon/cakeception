<?php

namespace Cakeception;

use Mockery as m;

class Application
{
	public function teardown()
	{
		m::teardown();
	}

	/**
	 * Gives a certain mocked class to be used
	 *
	 * @param string $key
	 * @return object {$key}
	 */
	public function give($key)
	{
		return m::mock($key);
	}
}
