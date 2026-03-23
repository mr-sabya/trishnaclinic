<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BedController extends Controller
{
    /**
     * Show the Main Bed List
     */
    public function index()
    {
        return view('admin.bed.index');
    }

    /**
     * Show Bed Types Management
     */
    public function bedType()
    {
        return view('admin.bed.bed-type');
    }

    /**
     * Show Bed Groups / Wards Management
     */
    public function bedGroup()
    {
        return view('admin.bed.bed-group');
    }

    /**
     * Show Floors Management
     */
    public function floor()
    {
        return view('admin.bed.floor');
    }
}
