<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

// GET work.php?name=Roman

// Перечисли суперглобальные массивы в PHP

// Get data from request superglobals
$name = $_GET['name'];

// Do some logic
$greetings = "Hello, $name";

// Make response
http_response_code(200);
header('Content-Type: text/plain; charset=UTF-8');
echo $greetings;
