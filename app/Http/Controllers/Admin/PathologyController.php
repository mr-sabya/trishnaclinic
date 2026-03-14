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
}
