<?php

use App\core\Kernel;
use App\Core\Http\Request;

define('ROOT_DIR', dirname(__DIR__));

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$kernel = new Kernel();
$request = Request::create();
$response = $kernel->handleRequest($request);
$response->send();
