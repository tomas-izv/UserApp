<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\VerifyMiddleware;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
        $this->middleware(VerifyMiddleware::class)->except(['index', 'verification']);
    }

    public function home()
    {
        return view('home');
    }

    public function index() {
        return view('index');
    }

    public function settings() {
        return view('settings');
    }
    
    public function verification() {
        return view('auth.verify');
    }
    
}
