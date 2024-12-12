<?php

namespace App\Http\Controllers\Posts;

use App\Services\Repositories\PostsRepositoryInterface;
use Bravik\MyPackage\CoolStaff;
use Illuminate\View\View;

class Index
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function __invoke(CoolStaff $coolStaff): View
    {
//        dd($coolStaff->sayHi("John", "Doe"));

        $posts = $this->postsRepository->fetchAll();

        return view("posts.index", compact("posts"));
    }
}
