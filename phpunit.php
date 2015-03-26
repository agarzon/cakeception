<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('UTC');

if ( ! is_file('vendor/autoload.php') ) {
	die('Composer autoload not found!');
}

require __DIR__.'/vendor/autoload.php';