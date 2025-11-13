<?php

namespace App\Http\Controllers\Recepcao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('recepcao.dashboard');
    }
}





