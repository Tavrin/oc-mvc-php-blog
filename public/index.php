<?php

use App\core\Kernel;
use App\Core\Http\Request;
use App\Core\Http\Response;

define('ROOT_DIR', dirname(__DIR__));

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$kernel = new Kernel();
$request = Request::create();
$response = $kernel->route($request);
$response->send();
