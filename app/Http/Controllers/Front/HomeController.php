<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\Permission;

use Auth;

class HomeController extends Controller
{
    public function dashboard(){
        $user = Auth::user();
        $user->load(['role']);

        $getRoleName = $user->getRoleName();

        /* Auth::logout();
        return redirect('login'); */

        echo $getRoleName . '<pre>'; print_r($user->toArray()); echo '</pre>'; exit;
        //dd($user);
        // assign role 
        // $role = Role::where('slug','editor')->first();
        // $user->roles()->attach($role);
        // dd($user->hasRole('author'));
        // 
        // check permission 
        // $permission = Permission::first();
        // $user->permissions()->attach($permission);
        // dd($user->permissions);
        // dd($user->can('add-post'));
        // 
        // 
        // 
        // 
        // dd($user->roles);
        // return view('dashboard');
    }

    public function index(){
        $allCategories = categoriesWithDescendants();
        //echo '<pre>'; print_r($allCategories->toArray()); echo '</pre>';
        return view('front.home.index');
    }
}
