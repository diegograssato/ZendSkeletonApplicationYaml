<?php
ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
//Zend\Mvc\Application::init(require 'config/application.config.yml')->run();
//// Declaring the yaml decoder
$decoder = new Symfony\Component\Yaml\Yaml();

// Loads the configurations
$reader = new Zend\Config\Reader\Yaml();
$reader->setYamlDecoder(array($decoder, 'parse'));
$config = $reader->fromFile( 'config/application.config.yml' );

// Runs the application
Zend\Mvc\Application::init( $config )->run();
