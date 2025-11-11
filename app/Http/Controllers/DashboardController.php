<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redireciona baseado no role
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('advogado')) {
            return redirect()->route('advogado.dashboard');
        } elseif ($user->hasRole('recepcionista') || $user->hasRole('tesoureiro')) {
            return redirect()->route('recepcao.dashboard');
        } elseif ($user->hasRole('cliente')) {
            return redirect()->route('cliente.dashboard');
        }
        
        return view('dashboard');
    }
}

