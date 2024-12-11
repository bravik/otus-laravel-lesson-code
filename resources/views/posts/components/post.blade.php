<div class="mt-6 post">
    <div class="max-w-4xl px-10 py-6 bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center">
            <span class="font-light text-gray-600">{{ $date }}</span>
{{--            <a class="px-2 py-1 bg-gray-600 text-gray-100 font-bold rounded hover:bg-gray-500" href="#">{{ $tag }}</a>--}}
        </div>
        <div class="mt-2">
            <a class="text-2xl text-gray-700 font-bold hover:underline post-title" href="{{ route('posts.show', ['postId' => $postId]) }}">{{ $title }}</a>
            <p class="mt-2 text-gray-600">{{ $body }}</p>
        </div>
        <div class="flex justify-between items-center mt-4">
            <a class="text-blue-500 hover:underline" href="{{ route('posts.show', ['postId' => $postId]) }}">Read more</a>
            <a class="text-blue-500 hover:underline" href="{{ route('posts.edit', ['post' => $post]) }}">Edit</a>
            <div>
                <a class="flex items-center" href="#">
                    <img class="mx-4 w-10 h-10 object-cover rounded-full hidden sm:block" :src="$image" alt="avatar">
                    <h1 class="text-gray-700 font-bold hover:underline">{{ $author }}</h1>
                </a>
            </div>
        </div>
    </div>
</div>
