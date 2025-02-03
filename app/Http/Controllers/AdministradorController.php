<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdministradorController extends Controller
{
    public function __construct()
    {
        $this->middleware(AdminMiddleware::class);
    }

    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $users = User::where('id', '<>', '1')->orderBy('name')->get();
        } else {
            $users = User::orderBy('name')->get();
        }

        $usersCount = User::count();
        return view('admin.index', compact('users', 'usersCount'));
    }


}
