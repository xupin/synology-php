<?php
/**
 * This file is an example on how to use Synology\Api
 */
/*
set_include_path(dirname(__FILE__) . '/src' . PATH_SEPARATOR . get_include_path());
*/
function __autoload($class_name)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
    include $path . '.php';
}
/*
require __DIR__ . '/vendor/autoload.php';
*/

$synology = new Synology\Api('192.168.10.5', 5001, 'https', 1);
$synology->activateDebug();
$synology->connect('admin', '****');
print_r($synology->getAvailableApi());