<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Gallery;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('author')
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $post)
    {
        abort_if(! $post->is_published || $post->published_at > now(), 404);

        return view('blog.show', compact('post'));
    }

    public function gallery()
    {
        $images = Gallery::where('is_active', true)
            ->whereNotNull('image')
            ->with('periodoEscolar')
            ->latest()
            ->paginate(15);

        return view('blog.gallery', compact('images'));
    }
}
