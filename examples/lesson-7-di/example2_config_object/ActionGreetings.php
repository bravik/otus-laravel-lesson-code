<?php

declare(strict_types=1);

namespace Lesson;

use Lesson\Request;
use Lesson\Response;

class ActionGreetings
{
    private Session $session;

    public function __construct(
        private bool $isPolite,
        int $sessionLifetime,
    ) {
        $this->session = new Session(
            $sessionLifetime
        );
    }

    function __invoke(Request $request): Response
    {
        // Get data from request headers
        $locale = $request->getLocale();

        // Get data from request super-globals
        $name = $request->getParameter('name');
        $lastName = $request->getBody('lastName');

        if ($locale === 'ru') {
            $greetings = ($this->isPolite ? "Добрый день" : "Здорова") . ", $name $lastName!";
        } else {
            $greetings = ($this->isPolite ? "Hello" : "Yo") . ", $name $lastName!";
        }

        $this->session->set('last_visitor', $name . ' ' . $lastName);

        return new Response($greetings, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }

}
