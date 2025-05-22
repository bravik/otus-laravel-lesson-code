<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Если хотите нормально использовать code-completion IDE, нужно проставить докблоки с типами переменных =(
 * Лучше вообще не использовать эти поля, и обернуть доступ к ним в геттеры
 *
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $text
 * @property User $author
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'text',
        'is_draft',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}
