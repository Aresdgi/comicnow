<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del usuario
     */
    public function index()
    {
        return view('user.dashboard');
    }
}