<?php

use App\core\Kernel;
use App\Core\Http\Request;

require dirname(__DIR__).'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$kernel = new Kernel();
$request = Request::create();
$kernel->route($request);
