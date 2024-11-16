<?php

declare(strict_types=1);

namespace Lesson;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;

function actionLastVisitor(Request $request): Response
{
    // Get data from session storage
    session_start();
    $lastVisitor = $_SESSION['last_visitor'] ?? null;

    // Get data from request headers
    $locale = $request->getLocale();

    // Get data from server environment variables
    $isPolite = $_ENV['IS_POLITE'] === 'true' ? true : false;

    if ($locale === 'ru') {
        $report = ($isPolite ? "Последний посетитель: " : "Был тут один тип! Этот: ") . "$lastVisitor!";
    } else {
        $report = ($isPolite ? "Last visitor: " : "Last guy showed up: ") . "$lastVisitor!";
    }

    return new Response(
        json_encode([
            'report' => $report,
        ]),
        200,
        [
            'Content-Type' => 'application/json; charset=utf-8',
        ]
    );
}
