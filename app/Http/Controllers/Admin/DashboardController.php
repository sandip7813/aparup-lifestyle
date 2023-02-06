<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth:web');
    }

    public function index(){
        $user = Auth::user();
        $user->load(['role']);

        $getRoleName = $user->getRoleName();

        /* Auth::logout();
        return redirect('login'); */

        //echo $getRoleName . '<pre>'; print_r($user->toArray()); echo '</pre>'; exit;

        return view('admin.dashboard.index');
    }
}
