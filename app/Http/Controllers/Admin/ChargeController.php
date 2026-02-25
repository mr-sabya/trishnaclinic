<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    // unit
    public function unit()
    {
        return view('admin.charge.unit');
    }

    // tax-categories
    public function taxCategories()
    {
        return view('admin.charge.tax-categories');
    }

    // charge-types
    public function chargeTypes()
    {
        return view('admin.charge.charge-types');
    }

    // charge-categories
    public function chargeCategories()
    {
        return view('admin.charge.charge-categories');
    }

    // charge
    public function charge()
    {
        return view('admin.charge.charge');
    }

    // tpa-charges
    public function tpaCharges()
    {
        return view('admin.charge.tpa-charges');
    }
}
