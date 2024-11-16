<?php

declare(strict_types=1);

namespace Lesson;

function actionLastVisitor()
{
    // Get data from session storage
    session_start();
    $lastVisitor = $_SESSION['last_visitor'] ?? null;

    // Get data from request headers
    $locale = 'en';
    if ($_SERVER['HTTP_ACCEPT_LANGUAGE']) {
        $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }


    // Get data from server environment variables
    $isPolite = $_ENV['IS_POLITE'] === 'true' ? true : false;



    $report = $locale === 'ru' ? 'Никого пока не было' : 'Nobody visited us yet';

    if ($lastVisitor !== null) {
        if ($locale === 'ru') {
            $report = ($isPolite ? "Последний посетитель: " : "Был тут один тип! Этот:") . ", $lastVisitor!";
        } else {
            $report = ($isPolite ? "Last visitor" : "Last guy showed up: ") . ", $lastVisitor!";
        }
    }

    // Make response
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'report' => $report,
    ]);
}
