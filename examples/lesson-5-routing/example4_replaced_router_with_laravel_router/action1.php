<?php

declare(strict_types=1);

namespace Lesson;



function actionGreetings(\Illuminate\Http\Request $request, string $name): \Illuminate\Http\Response
{
    // Get data from request headers
    $locale = $request->getLocale();

    // Get data from server environment variables
    $isPolite = $_ENV['IS_POLITE'] === 'true' ? true : false;

    // Get data from request superglobals
    $lastName = $request->post('lastName');

    if ($locale === 'ru') {
        $greetings = ($isPolite ? "Добрый день" : "Здорова") . ", $name $lastName!";
    } else {
        $greetings = ($isPolite ? "Hello" : "Yo") . ", $name $lastName!";
    }

    session_start();
    $_SESSION['last_visitor'] = $name . ' ' . $lastName;

    return new \Illuminate\Http\Response($greetings, 200, [
        'Content-type' => 'text/plain; charset=utf-8',
    ]);
}
