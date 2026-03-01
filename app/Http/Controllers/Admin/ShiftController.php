<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    // global shift
    public function globalShift()
    {
        return view('admin.global-shift.index');
    }

    // index
    public function index()
    {
        return view('admin.doctor-schedule.index');
    }

    // create
    public function create()
    {
        return view('admin.doctor-schedule.create');
    }

    // edit
    public function edit($id)
    {
        $schedule = DoctorSchedule::findOrFail(intval($id));
        return view('admin.doctor-schedule.edit', compact('schedule'));
    }
}
