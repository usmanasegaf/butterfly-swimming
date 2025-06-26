<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;

class MuridDashboardController extends Controller
{
    public function index()
    {
        return view('murid.dashboard');
    }
}