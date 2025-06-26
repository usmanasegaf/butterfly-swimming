<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class GuruDashboardController extends Controller
{
    public function index()
    {
        return view('guru.dashboard');
    }
}