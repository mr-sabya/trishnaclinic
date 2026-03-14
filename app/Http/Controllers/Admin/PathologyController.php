<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PathologyController extends Controller
{
    //
    public function unit()
    {
        return view('admin.pathology.unit');
    }

    // category
    public function category()
    {
        return view('admin.pathology.category');    
    }

    // parameter
    public function parameter()
    {
        return view('admin.pathology.parameter');    
    }

    // parameter
    public function test()
    {
        return view('admin.pathology.test');    
    }
}
