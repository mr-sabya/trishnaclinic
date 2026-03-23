<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use Illuminate\Http\Request;

class IpdController extends Controller
{
    /**
     * Display a listing of IPD Admissions.
     */
    public function index()
    {
        return view('admin.ipd.index');
    }

    /**
     * Show the form for creating a new IPD Admission.
     */
    public function create()
    {
        return view('admin.ipd.create');
    }

    /**
     * Show the form for editing the specified IPD Admission.
     */
    public function edit($id)
    {
        $ipd = IpdAdmission::findOrFail(intval($id));
        return view('admin.ipd.edit', compact('ipd'));
    }

    /**
     * Display the Patient Dashboard/Profile.
     */
    public function show($id)
    {
        $ipd = IpdAdmission::findOrFail(intval($id));
        return view('admin.ipd.show', compact('ipd'));
    }
}