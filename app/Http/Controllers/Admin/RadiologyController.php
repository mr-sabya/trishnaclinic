<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RadiologyController extends Controller
{
    //
    public function unit()
    {
        return view('admin.radiology.unit');
    }

    // category
    public function category()
    {
        return view('admin.radiology.category');
    }

    // parameter
    public function parameter()
    {
        return view('admin.radiology.parameter');
    }

    // parameter
    public function test()
    {
        return view('admin.radiology.test');
    }
}
