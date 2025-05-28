<?php

declare(strict_types=1);

namespace Lesson;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActionNotFound {

    public function __invoke(Request $request): Response
    {

        return new Response(
            "<h1>Action for URL " . $request->path() . " was not found</h1>",
            200,
            [
                'Content-Type' => 'text/html; charset=utf-8',
            ]
        );
    }

}
