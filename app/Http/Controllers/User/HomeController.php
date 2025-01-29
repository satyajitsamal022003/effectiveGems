<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('user.index', compact('banners'));
    }
}
