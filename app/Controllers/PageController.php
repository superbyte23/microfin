<?php

namespace App\Controllers;

use App\Models\User;
use App\View;

class PageController extends AuthController
{
    public function index()
    {
        $users = User::all();
        return View::render('home', ['users' => $users]);
    }

    public function dashboard()
    { 
        return View::render('dashboard', ['titlePage' => "Welcome to Dashboard"]);
    }

    public function about()
    {
        return View::render('about');
    }
}
