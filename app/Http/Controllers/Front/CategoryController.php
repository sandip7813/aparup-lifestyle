<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Categories;

class CategoryController extends Controller
{
    public function posts($slug){
        //$allCategories = categoriesWithDescendants();
        //echo '<pre>'; print_r($allCategories->toArray()); echo '</pre>';
        
        $category = Categories::withCount('blogs')->where('slug', $slug)->first();

        if( !isset($category->id) ){
            abort(404);
        }

        //echo '<pre>'; print_r($category->toArray()); echo '</pre>';

        return view('front.category.posts', compact('category'));
    }
}
