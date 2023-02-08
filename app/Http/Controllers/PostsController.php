<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\QueryBuilders\PostQueryBuilder;

class PostsController extends Controller
{
    public function index()
    {
        $years = Post::query()
            ->select('id', 'title', 'slug', 'published_at', 'author_id') // without -- memory usage higher
            ->with('author:id,first_name,last_name') // without -- memory usage higher
            ->latest('published_at')
            ->get()
            ->groupBy(fn ($post) => $post->published_at->year);

        return view('posts', ['years' => $years]);
    }

    public function index2()
    {
        $posts = Post::query()
            ->with('author')
            ->when(request('search'), function (PostQueryBuilder $query, $search) {
                $query
                    ->select('*')
                    ->addSelectMatchTitleOrBodyAsScore($search)
                    ->whereMatchTitleOrBody($search)
//                    ->whereLikeTitleOrBody($search)
                ;
            }, function ($query) {
                $query->latest('published_at');
            })
            ->paginate();

        return view('posts2', ['posts' => $posts]);
    }
}
