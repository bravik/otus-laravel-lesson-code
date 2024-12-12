<?php

declare(strict_types=1);

namespace App\Models;

enum Role: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case USER = 'user';
}
