<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    //
    public function SymptomType()
    {
        return view('admin.symptom.symptom-type');
    }

    // SymptomTitle
    public function SymptomTitle()
    {
        return view('admin.symptom.symptom-title');    
    }
}
