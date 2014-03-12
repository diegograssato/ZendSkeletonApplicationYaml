<?php
ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Loads the configurations
Zend\Config\Factory::registerReader('yml', 'yaml');
$reader = new Zend\Config\Reader\Yaml();
$reader  = Zend\Config\Factory::getReaderPluginManager()->get('yaml');
$reader->setYamlDecoder(array(new Symfony\Component\Yaml\Yaml(), 'parse'));
$config = $reader->fromFile('config/application.yml');

// Runs the application
Zend\Mvc\Application::init( $config )->run();
