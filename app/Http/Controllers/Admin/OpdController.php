<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OpdAdmission;
use Illuminate\Http\Request;

class OpdController extends Controller
{
    /**
     * Display a listing of OPD Admissions.
     */
    public function index()
    {
        return view('admin.opd.index');
    }

    /**
     * Show the form for creating a new OPD Admission.
     */
    public function create()
    {
        return view('admin.opd.create');
    }

    /**
     * Show the form for editing the specified OPD Admission.
     */
    public function edit($id)
    {
        $opd = OpdAdmission::findOrFail(intval($id));
        return view('admin.opd.edit', compact('opd'));
    }

    /**
     * Display the Patient Dashboard/Profile.
     */
    public function show($id)
    {
        $opd = OpdAdmission::findOrFail(intval($id));
        return view('admin.opd.show', compact('opd'));
    }
}