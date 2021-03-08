<?php

use Core\Kernel;
use Core\http\Request;

define('ROOT_DIR', dirname(__DIR__));

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT_DIR, '.env.local');

$dotenv->load();

$kernel = new Kernel();
$request = Request::create();
$response = $kernel->handleRequest($request);
$response->send();
exit();
