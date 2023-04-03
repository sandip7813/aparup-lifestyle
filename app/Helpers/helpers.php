<?php

use App\Models\Categories;

if (!function_exists('categoriesWithDescendants')) {
    function categoriesWithDescendants(){
        return Categories::with(['descendants' => function($q) {
                                $q->where('status', '1');
                            }])
                            ->where('status', '1')
                            ->get();
    }
}