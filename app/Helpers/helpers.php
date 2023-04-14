<?php

use App\Models\Categories;
use App\Models\Comments;

if (!function_exists('categoriesWithDescendants')) {
    function categoriesWithDescendants(){
        return Categories::with(['descendants' => function($q) {
                                $q->where('status', '1');
                            }])
                            ->where('status', '1')
                            ->get();
    }
}

if (!function_exists('allRootCategoriesWithBlogsCount')) {
    function allRootCategoriesWithBlogsCount(){
        return Categories::withCount('blogs')
                            ->where('status', '1')
                            ->get();
    }
}

if (!function_exists('commentsWithDescendants')) {
    function commentsWithDescendants($blog_uuid){
        return Comments::with(['descendants' => function($q) {
                                $q->where('status', '1');
                            }])
                            ->where('blog_uuid', $blog_uuid)
                            ->whereNull('parent_id')
                            ->where('status', '1')
                            ->get();
    }
}