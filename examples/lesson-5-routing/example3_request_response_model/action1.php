<?php

declare(strict_types=1);

namespace Lesson;

use example4_work_final\Request;
use example4_work_final\Response;

function actionGreetings(Request $request): Response
{
    // Get data from request headers
    $locale = $request->getLocale();

    // Get data from server environment variables
    $isPolite = $_ENV['IS_POLITE'] === 'true' ? true : false;

    // Get data from request superglobals
    $name = $request->getParameter('name');
    $lastName = $request->getBody('lastName');

    if ($locale === 'ru') {
        $greetings = ($isPolite ? "Добрый день" : "Здорова") . ", $name $lastName!";
    } else {
        $greetings = ($isPolite ? "Hello" : "Yo") . ", $name $lastName!";
    }

    session_start();
    $_SESSION['last_visitor'] = $name . ' ' . $lastName;

    return new Response($greetings, 200, [
        'Content-Type' => 'text/plain; charset=utf-8',
    ]);
}
