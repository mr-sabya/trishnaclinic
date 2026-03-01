<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // Specialist
    public function specialist()
    {
        return view('admin.specialist.index');
    }

    // index
    public function index()
    {
        return view('admin.doctor.index');
    }

    // create
    public function create()
    {
        return view('admin.doctor.create');
    }

    // edit doctor
    public function edit($id)
    {
        $doctor = Doctor::findOrFail(intval($id));
        return view('admin.doctor.edit', compact('doctor'));
    }
}
