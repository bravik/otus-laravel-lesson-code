@php
    /**
     * @var int|null $postId - The ID of the post (for edit form)
     * @var string|null $title - The title of the post
     * @var string|null $text - The text of the post
     * @var int|null $authorId - The ID of the author
     */
@endphp

@extends('layout')

@section('title', isset($postId) ? 'Edit Post' : 'Create Post')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold my-4">
            {{ isset($postId) ? 'Edit Post' : 'Create Post'}}
        </h1>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ isset($postId) ? route('posts.update', $postId) : route('posts.store') }}" method="POST">
            @csrf
            @if (isset($postId))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $title ?? '') }}"
                       class="{{$errors->has('title') ? 'invalid' : ''}}"
                       required>
            </div>

            <div class="mb-4">
                <label for="text">Text</label>
                <textarea name="text" id="text" rows="5" class=" {{$errors->has('title') ? 'invalid' : ''}}" required>{{ old('text', $text ?? '') }}</textarea>
            </div>

            <div>
                <button type="submit" class="">
                    {{ isset($postId) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
@endsection
