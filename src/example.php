<?php
/**
 * This file is an example on how to use Synology\Api
 */
require dirname(__DIR__) . '/vendor/autoload.php';

$synology = new Synology\Api('192.168.10.5', 5001, 'https', 1);
$synology->activateDebug();
$synology->connect('admin', '****');
print_r($synology->getAvailableApi());
