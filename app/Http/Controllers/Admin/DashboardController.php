<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blogs;
use App\Models\Categories;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth:web');
    }

    public function index(){
        $totalActiveBlogs = Blogs::where('page_type', 'blog_page')->where('status', '1')->count();
        $totalDraftedBlogs = Blogs::where('page_type', 'blog_page')->where('status', '2')->count();
        $totalInactiveBlogs = Blogs::where('page_type', 'blog_page')->where('status', '0')->count();
        $totalActiveCategories = Categories::where('status', '1')->count();

        $recentActiveBlogs = Blogs::with(['categories', 'banner'])
                                    ->where('page_type', 'blog_page')->where('status', '1')
                                    ->orderBy('updated_at', 'DESC')->limit(5)->get();
        
        $recentCategories = Categories::with('blogs')->where('status', '1')
                                        ->orderBy('updated_at', 'DESC')->limit(5)->get();

        return view('admin.dashboard.index', compact('totalActiveBlogs', 'totalDraftedBlogs', 'totalInactiveBlogs', 'totalActiveCategories', 'recentActiveBlogs', 'recentCategories'));
    }
}
