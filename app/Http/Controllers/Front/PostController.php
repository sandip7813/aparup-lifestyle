<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blogs;

class PostController extends Controller
{
    public function postDetails($slug){
        $post = Blogs::with(['banner', 'categories', 'contents'])
                    ->where('slug', $slug)->first();

        if( !isset($post->id) ){
            abort(404);
        }

        echo '<pre>'; print_r($post->toArray()); echo '</pre>';
        return view('front.post.details', compact('post'));
    }
}
