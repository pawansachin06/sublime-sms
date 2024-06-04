<?php

namespace App\Http\Controllers;

use App\Services\SMSApi;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $smsApi;

    function __construct(SMSApi $smsApi)
    {
        $this->smsApi = $smsApi;
    }

    public function dashboard(Request $req)
    {
        session()->flash('flash.banner', 'Website Under Active Development');
        session()->flash('flash.bannerStyle', 'danger');

        // dd($this->smsApi->get_balance());
        return view('dashboard');
    }

}
