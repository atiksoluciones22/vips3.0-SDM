<?php

namespace App\Http\Controllers\HumanCapital;

use App\Http\Controllers\Controller;

class ActionRequestController extends Controller
{
    public function index()
    {
        return view('human-capital.action-request.index');
    }
}
