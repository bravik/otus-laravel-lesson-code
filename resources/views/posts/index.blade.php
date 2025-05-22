@php use App\Models\Post; @endphp
@extends('layout')

@php
/**
 * @var Post[] $posts
 */
@endphp

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-700 dark:text-gray-100 md:text-2xl">Posts</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <a href="{{ route('posts.create') }}">Create Post</a>
        </button>
    </div>

{{--    // TODO DO not use session in templates--}}
    @if (session()->has('success'))
        <div class="bg-green-200 p-6 rounded-xl border-2 border-green-300">
            {{ session('success') }}
        </div>
    @endif

    @foreach($posts as $post)
            @include('posts.components.post', [
                'postId' => $post->id,
                'date' => $post->created_at->format('F j, Y'),
                'title' => $post->title,
                'body' => $post->text,
                'author' => $post->author->name,
                'post' => $post,
            ])
    @endforeach

    <div class="mt-8">
        <ul class="flex">
            <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-500 rounded-lg">
                <a class="flex items-center font-bold" href="#">previous</a>
            </li>
            <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg">
                <a class="font-bold" href="#">1</a>
            </li>
            <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg">
                <a class="font-bold" href="#">2</a>
            </li>
            <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg">
                <a class="font-bold" href="#">3</a>
            </li>
            <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg">
                <a class="flex items-center font-bold" href="#">Next</a>
            </li>
        </ul>
    </div>
@endsection
