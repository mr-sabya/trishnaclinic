<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // admin department index
    public function adminDepartmentIndex()
    {
        return view('admin.department.admin-department');
    }
}
