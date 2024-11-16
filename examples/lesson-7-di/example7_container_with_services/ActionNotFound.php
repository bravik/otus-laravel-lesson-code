<?php

declare(strict_types=1);

namespace Lesson;


class ActionNotFound {

    function __invoke(Request $request): Response
    {

        return new Response(
            "<h1>Action for URL " . $request->getPath() . " was not found</h1>",
            200,
            [
                'Content-Type' => 'text/html; charset=utf-8',
            ]
        );
    }

}
