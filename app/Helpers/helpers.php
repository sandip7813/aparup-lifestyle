<?php

use App\Models\Categories;

if (!function_exists('categoriesWithDescendants')) {
    function categoriesWithDescendants(){
        return Categories::with('descendants')
                            //->where()
                            ->get();
    }
}