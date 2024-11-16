<?php

declare(strict_types=1);

namespace Lesson;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
class ActionGreetings
{
    public function __construct(
        private bool $isPolite,
        private Session $session,
    ) {
    }

    function __invoke(Request $request, string $name): Response
    {
        // Get data from request headers
        $locale = $request->getLocale();

        // Get data from request super-globals
        $lastName = $request->get('lastName');

        if ($locale === 'ru') {
            $greetings = ($this->isPolite ? "Добрый день" : "Здорова") . ", $name $lastName!";
        } else {
            $greetings = ($this->isPolite ? "Hello" : "Yo") . ", $name $lastName!";
        }

        $this->session->put('last_visitor', $name . ' ' . $lastName);

        return new Response($greetings, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }

}
