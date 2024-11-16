<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Http\Request;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../../vendor/autoload.php';


$container = new Container();

require __DIR__ . '/services.php';
require __DIR__ . '/routes.php';


// Run program
/** @var Request $request */
$request = $container->get('request');
$router = $container->get('router');

$response = $router->dispatch($request);
$response->send();
