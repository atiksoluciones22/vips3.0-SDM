<?php

namespace App\Http\Controllers\HumanCapital;

use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('human-capital.employee.index');
    }
}
