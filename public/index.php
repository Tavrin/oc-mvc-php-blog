<?php

use Core\Kernel;
use Core\http\Request;

define('ROOT_DIR', dirname(__DIR__));

require dirname(__DIR__) . '/vendor/autoload.php';
header('Access-Control-Allow-Origin: *');

$dotenv = Dotenv\Dotenv::createImmutable(ROOT_DIR, '.env.local');
$dotenv->load();
$request = Request::create();
$kernel = new Kernel();
$response = $kernel->handleRequest($request);
$response->send();
exit();
