<?php

declare(strict_types=1);

namespace Lesson;

function actionGreetings()
{
    // Get data from request headers
    $locale = 'en';
    if ($_SERVER['HTTP_ACCEPT_LANGUAGE']) {
        $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }


    // Get data from server environment variables
    $isPolite = $_ENV['IS_POLITE'] === 'true' ? true : false;

    // Get data from request superglobals
    $name = $_GET['name'];
    $lastName = $_POST['lastName'];

    if ($locale === 'ru') {
        $greetings = ($isPolite ? "Добрый день" : "Здорова") . ", $name $lastName!";
    } else {
        $greetings = ($isPolite ? "Hello" : "Yo") . ", $name $lastName!";
    }

    session_start();
    $_SESSION['last_visitor'] = $name . ' ' . $lastName;

    // Make response
    http_response_code(200);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $greetings;
}
