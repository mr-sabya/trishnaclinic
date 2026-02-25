<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // index
    public function index()
    {
        return view('admin.patient.index');
    }

    // create
    public function create()
    {
        return view('admin.patient.create');
    }

    // edit
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('admin.patient.edit', compact('patient'));
    }
}
