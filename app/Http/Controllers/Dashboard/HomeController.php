<?php

namespace Gcr\Http\Controllers\Dashboard;

use Gcr\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('dashboard.home');
    }
}
