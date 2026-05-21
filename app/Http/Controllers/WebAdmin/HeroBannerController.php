<?php

namespace App\Http\Controllers\WebAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeroBannerController extends Controller
{
    public function index()
    {
        return view('heroBanner.index');
    }

    public function create()
    {
        return view('heroBanner.create');
    }

    public function edit($id)
    {
        return view('heroBanner.edit', compact('id'));
    }
}
