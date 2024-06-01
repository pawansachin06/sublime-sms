<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{

    public function dashboard(Request $req)
    {
        session()->flash('flash.banner', 'Website Under Active Development');
        session()->flash('flash.bannerStyle', 'danger');
        return view('dashboard');
    }

}
