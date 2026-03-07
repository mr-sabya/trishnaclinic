<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    //
    public function index()
    {
        return view('admin.appointment.index');    
    }

    // create
    public function create()
    {
        return view('admin.appointment.create');    
    }

    // edit
    public function edit($id)
    {
        $appointment = Appointment::findOrFail(intval($id));
        return view('admin.appointment.edit', compact('appointment'));    
    }
}
