<?php

use App\Models\Categories;
use App\Models\Comments;

if (!function_exists('categoriesWithDescendants')) {
    function categoriesWithDescendants(){
        return Categories::with('descendants')
                            ->whereNull('parent_id')
                            ->where('status', '1')
                            ->get();
    }
}

if (!function_exists('allRootCategoriesWithBlogsCount')) {
    function allRootCategoriesWithBlogsCount(){
        return Categories::withCount('blogs')
                            ->whereNull('parent_id')
                            ->where('status', '1')
                            ->get();
    }
}

if (!function_exists('commentsWithDescendants')) {
    function commentsWithDescendants($blog_uuid){
        return Comments::with('descendants')
                            ->where('blog_uuid', $blog_uuid)
                            ->whereNull('parent_id')
                            ->where('status', '1')
                            ->get();
    }
}