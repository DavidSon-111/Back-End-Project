@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-7 rounded-lg">

{{--            თუ იუზერი არაა დალოგინებული მასთან არ გამოჩნდება პოსტის დასაწერი ფორმა--}}
            @auth
            <form action="{{route('posts')}}" method="post" class="mb-4">
                @csrf
                <div class="mb-4">
                    <label for="body" class="sr-only">Body</label>
                    <textarea name="body" cols="30" rows="4"
                              class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('body') border-red-500 @enderror"
                              placeholder="What's on your mind?"></textarea>

                    @error('body')
                        <div class="text-red-500 mt-2 text-sm">
                            {{$message}}
                        </div>
                    @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 font-medium rounded" >Post</button>
                </div>
            </form>
            @endauth

            @if($posts->count())
                @foreach($posts as $post)
                    <div class="mb-4">
                        <a href="{{route('users.posts', $post->user)}}" class="font-bold">{{$post->user->name}}</a>
                        <span class="text-gray-600 text-sm">{{$post->created_at->diffForHumans()}}</span>
                        <p class="mb-2">{{$post->body}}</p>

{{--                        can დირექტივით ვამოწმებთ შეგვიძლია თუ არა პოსტის წაშლა და შესაბამისად
                            გამოგვაქვს წაშლის ღილაკი--}}
                        @can('delete', $post)
                        <form action="{{route('posts.destroy', $post)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-blue-500">Delete</button>
                        </form>
                        @endcan

                        <div class="flex items-center">
{{--                            post pageზე გადასვლილას log in ის გარეშე ერორს აღარ მოგვცემს--}}
                            @auth
{{--                            თუ დალაიქებულია like buttonს აღარ აჩვენებს--}}
                                @if(!$post->likedBy(auth()->user()))
                                    <form action="{{route('posts.like', $post->id)}}" method="post" class="mr-1">
                                        @csrf
                                        <button type="submit" class="text-blue-500">Like</button>
                                    </form>
                                @else
                                    <form action="{{route('posts.like', $post->id)}}" method="post" class="mr-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-blue-500">Dislike</button>
                                    </form>
                                @endif
                            @endauth
                            <span>{{$post->likes->count()}} {{\Illuminate\Support\Str::plural('like',
                                    $post->likes->count())}}</span>
                        </div>
                    </div>
                @endforeach

                {{$posts->links()}}
            @else
                <p>There are no posts!</p>
            @endif
        </div>
    </div>
@endsection
