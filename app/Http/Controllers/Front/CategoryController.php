<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Categories;
use App\Models\Blogs;

class CategoryController extends Controller
{
    public function posts($slug){
        $category = Categories::withCount('blogs')->where('slug', $slug)->first();

        if( !isset($category->id) ){
            abort(404);
        }

        $category_id = $category->id;

        $topPosts = Blogs::with('banner')
                            ->whereHas('categories', function($q) use($category_id) {
                                $q->where('id', $category_id);
                            })
                            ->where('page_type', 'blog_page')
                            ->where('status', '1')
                            ->orderBy('updated_at', 'DESC')
                            ->limit(6)
                            ->get();
        
        $topPostIds = [];

        if( $topPosts->count() > 0 ){
            foreach($topPosts as $post){
                $topPostIds[] = $post->id;
            }
        }

        $mainPosts = Blogs::with('banner')
                        ->whereHas('categories', function($q) use($category_id) {
                            $q->where('id', $category_id);
                        })
                        ->where('page_type', 'blog_page')
                        ->where('status', '1')
                        ->whereNotIn('id', $topPostIds)
                        ->orderBy('updated_at', 'DESC')
                        ->paginate(12);

        return view('front.category.posts', compact('category', 'topPosts', 'mainPosts'));
    }
}
