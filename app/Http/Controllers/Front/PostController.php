<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blogs;

class PostController extends Controller
{
    public function postDetails($slug){
        $post = Blogs::with(['banner', 'categories', 'contents'])
                    ->where('slug', $slug)
                    ->where('page_type', 'blog_page')
                    ->where('status', '1')
                    ->first();

        if( !isset($post->id) ){
            abort(404);
        }

        $category_ids = [];

        if( $post->categories->count() > 0 ){
            foreach($post->categories as $category){
                $category_ids[] = $category->id;
            }
        }

        $relatedPosts = Blogs::with('banner')
                            ->whereHas('categories', function($q) use($category_ids) {
                                $q->whereIn('id', $category_ids);
                            })
                            ->where('slug', '!=', $slug)
                            ->where('page_type', 'blog_page')
                            ->where('status', '1')
                            ->inRandomOrder()
                            ->limit(2)
                            ->get();

        return view('front.post.details', compact('post', 'relatedPosts'));
    }
}
