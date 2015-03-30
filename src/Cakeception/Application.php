<?php

namespace Cakeception;

class Application
{
	/**
	 * Options container
	 *
	 * @var array
	 * @access protected
	 */
	protected $options = [];

	/**
	 * Container for Cake core libraries with assigned internal namespacing
	 *
	 * @var array $libraries
	 * @access protected
	 */
	protected $libraries = [];

	/**
	 * Namespacing alias prefix
	 *
	 * @var string $aliasPrefix
	 * @access protected
	 */
	protected $aliasPrefix = 'Cake\\';

	public function __construct(array $options = [])
	{
		$this->options = $options;
	}

	/**
	 * Return the options array
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Registers libraries into a self contained namespace
	 *
	 * @param array $libraries
	 */
	public function autoload(array $libraries)
	{
		foreach ($libraries as $namespace => $params) {
			$this->parseParams($namespace, $params);
		}
	}

	/**
	 * Parse the namespace and parameters of the autoloader
	 *
	 * @param string $key
	 * @param array $params
	 */
	public function parseParams($namespace, $params)
	{

	}

	/**
	 * Assign internal namespacing to be used by internal functions
	 *
	 * @param string $key
	 * @param string $value
	 */
	protected function namespaceLib($key, $value)
	{
		$this->libraries[$this->aliasPrefix . $key] = $file;

		spl_autoload_register(function($file) {
			$file = preg_match("/^Cake\\/", $file) ? implode('/', array_shift(explode('\\', $file))) : $file;

			include $file;
		});
	}

}
