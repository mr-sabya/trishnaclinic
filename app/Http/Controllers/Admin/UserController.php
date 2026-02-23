<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //index
    public function index()
    {
        return view('admin.user.index');
    }

    // create
    public function create()
    {
        return view('admin.user.create');
    }

    // edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }
}
