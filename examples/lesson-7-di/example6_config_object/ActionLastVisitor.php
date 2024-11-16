<?php

declare(strict_types=1);

namespace Lesson;

use Lesson\Request;
use Lesson\Response;

class ActionLastVisitor {
    private Session $session;

    public function __construct(
        private bool $isPolite,
        int $sessionLifetime,
    ) {
        $this->session = new Session(
            $sessionLifetime
        );
    }

    public function __invoke(Request $request): Response
    {
        // Get data from session storage
        $lastVisitor = $this->session->get('last_visitor', 'Anonymous');

        // Get data from request headers
        $locale = $request->getLocale();

        // Get data from server environment variables

        if ($locale === 'ru') {
            $report = ($this->isPolite ? "Последний посетитель: " : "Был тут один тип! Этот: ") . "$lastVisitor!";
        } else {
            $report = ($this->isPolite ? "Last visitor: " : "Last guy showed up: ") . "$lastVisitor!";
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

}
