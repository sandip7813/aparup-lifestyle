<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\CategoryController as FrontCategoryController;
use App\Http\Controllers\Front\PostController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MyAccountController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BlogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
}); */

/* Route::group(['middleware'=>'auth'],function(){
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
}); */

//+++++++++++++++++++++++ FRONT ROUTE :: Start +++++++++++++++++++++++//
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/category/{slug}', [FrontCategoryController::class, 'posts'])->name('category.posts');
Route::get('/post/{slug}', [PostController::class, 'postDetails'])->name('post.details');
//+++++++++++++++++++++++ FRONT ROUTE :: End +++++++++++++++++++++++//

//+++++++++++++++++++++++ ADMIN ROUTE :: Start +++++++++++++++++++++++//
Route::middleware(['auth'])->prefix('admin')->group(function(){
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');

    //+++++++++++++++++++++++++ MY PROFILE UPDATE :: Start +++++++++++++++++++++++++//
    Route::get('myaccount/change-password', [MyAccountController::class, 'changePassword'])->name('admin.myaccount.change-password');
    Route::post('myaccount/change-password-submit', [MyAccountController::class, 'changePasswordSubmit'])->name('admin.myaccount.change-password-submit');
    Route::get('myaccount/update-profile', [MyAccountController::class, 'updateProfile'])->name('admin.myaccount.update-profile');
    Route::post('myaccount/update-profile-submit', [MyAccountController::class, 'updateProfileSubmit'])->name('admin.myaccount.update-profile-submit');
    Route::post('myaccount/delete-profile-picture', [MyAccountController::class, 'deleteProfilePicture'])->name('admin.myaccount.delete-profile-picture');
    //+++++++++++++++++++++++++ MY PROFILE UPDATE :: End +++++++++++++++++++++++++//
    
    //+++++++++++++++++++++++++ CATEGORIES :: Start +++++++++++++++++++++++++//
    Route::get('categories', [CategoryController::class, 'index'])->name('admin.category.index');
    Route::get('category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('category/add-submit', [CategoryController::class, 'addCategorySubmit'])->name('admin.category.add-submit');
    Route::get('category/{uuid}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::post('category/{uuid}/update-submit', [CategoryController::class, 'updateCategorySubmit'])->name('admin.category.update-submit');
    Route::post('category/change-status', [CategoryController::class, 'changeCategoryStatus'])->name('admin.category.change-status');
    Route::post('category/{uuid}/delete-item', [CategoryController::class, 'deleteCategory'])->name('admin.category.delete-item');
    Route::post('category/regenerate-slug', [CategoryController::class, 'regenerateSlug'])->name('admin.category.regenerate-slug');
    //+++++++++++++++++++++++++ CATEGORIES :: End +++++++++++++++++++++++++//

    //+++++++++++++++++++++++++ BLOGS :: Start +++++++++++++++++++++++++//
    Route::get('blogs', [BlogController::class, 'index'])->name('admin.blog.index');
    Route::get('blog/redirect-to-create', [BlogController::class, 'redirectToCreate'])->name('admin.blog.redirect-to-create');
    Route::get('blog/{uuid}/create', [BlogController::class, 'create'])->name('admin.blog.create');
    Route::post('blog/submit', [BlogController::class, 'blogSubmit'])->name('admin.blog.submit');
    Route::post('blog/delete-item', [BlogController::class, 'deleteBlog'])->name('admin.blog.delete-item');
    Route::post('blog/change-banner', [BlogController::class, 'changeBanner'])->name('admin.blog.change-banner');
    Route::get('blog/{uuid}/edit', [BlogController::class, 'edit'])->name('admin.blog.edit');
    Route::post('blog/regenerate-slug', [BlogController::class, 'regenerateSlug'])->name('admin.blog.regenerate-slug');
    Route::post('blog/add-blog-content', [BlogController::class, 'addBlogContent'])->name('admin.blog.add-blog-content');
    Route::post('blog/add-blog-content-images', [BlogController::class, 'addBlogContentImages'])->name('admin.blog.add-blog-content-images');
    Route::post('blog/upload-blog-content-image', [BlogController::class, 'uploadBlogContentImage'])->name('admin.blog.upload-blog-content-image');
    Route::post('blog/delete-blog-content-image-row', [BlogController::class, 'deleteBlogContentImageRow'])->name('admin.blog.delete-blog-content-image-row');
    Route::get('blog/download-blog-content-image', [BlogController::class, 'downloadBlogContentImage'])->name('admin.blog.download-blog-content-image');
    Route::post('blog/save-blog-content-image-fields', [BlogController::class, 'saveBlogContentImageFields'])->name('admin.blog.save-blog-content-image-fields');
    Route::post('blog/save-blog-content-fields', [BlogController::class, 'saveBlogContentFields'])->name('admin.blog.save-blog-content-fields');
    Route::post('blog/delete-blog-content-row', [BlogController::class, 'deleteBlogContentRow'])->name('admin.blog.delete-blog-content-row');
    Route::get('blog/download-banner-image', [BlogController::class, 'downloadBannerImage'])->name('admin.blog.download-banner-image');
    //+++++++++++++++++++++++++ BLOGS :: End +++++++++++++++++++++++++//
});
//+++++++++++++++++++++++ ADMIN ROUTE :: End +++++++++++++++++++++++//

require __DIR__.'/auth.php';
