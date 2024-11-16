<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'reply_to',
        'author_id',
        'text',
        'entity',
        'entity_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'author_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'reply_to', 'id');
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'reply_to', 'id');
    }
}
