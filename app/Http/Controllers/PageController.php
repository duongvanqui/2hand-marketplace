<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about() {
        return view('pages.about');
    }

    public function regulations() {
        return view('pages.regulations');
    }

    public function privacy() {
        return view('pages.privacy');
    }

    public function help() {
        return view('pages.help');
    }

    public function safeTrading() {
        return view('pages.safe_trading');
    }

    public function codGuide() {
        return view('pages.cod_guide');
    }
}