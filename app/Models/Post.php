<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

/**
 * Если хотите нормально использовать code-completion IDE, нужно проставить докблоки с типами переменных =(
 * Лучше вообще не использовать эти поля, и обернуть доступ к ним в геттеры
 *
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

    /**
     * Конструктор имеет параметр $attributes, заданный в родительской модели Eloquent.
     * Это делает его для нас бесполезным, если мы хотим создавать модель сразу в целостном состоянии
     * - мы не можем передать в него типизированные атрибуты по желанию.
     *
     * Поэтому мы его помечаем
     * @deprecated Используете статический метод create()
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Альтернативный конструктор через статический фабричный метод
     */
    public static function create(string $title, string $text, User $author): static
    {
        $post = new self();
        $post->title = $title;
        $post->text = $text;
        $post->author = $author;

        return $post;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getText(): string
    {
        return $this->text;
    }


    /**
     * Пример мутатора как альтернатива прямой записи в свойства или сеттерам.
     * Мутаторы переводят модель из одного валидного состояния в другое.
     * Имеют правила валидации (Ассерты), чтобы обеспечить это требования
     */
    public function updateContent(
        string $title,
        string $text
    ): void {
        if (empty($title)) {
            throw new \InvalidArgumentException("Empty title");
        }

        // Или используя Webmozart/Assert
        Assert::stringNotEmpty($title);
        Assert::stringNotEmpty($text);

        $this->setAttribute('title', $title);
        $this->setAttribute('text', $text);
    }

    /**
     * Еще пример осмысленного мутатора, который не допускает перевод модели в опубликованное состояние,
     * если не соблюдаются условия.
     */
    public function publish(): void
    {
        if ($this->title === '' || $this->text === '') {
            throw new \LogicException('Post must have title and text');
        }

        $this->is_draft = false;
    }


    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}
