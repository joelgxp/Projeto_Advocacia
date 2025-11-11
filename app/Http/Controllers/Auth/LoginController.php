<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|email',
            'senha' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->usuario,
            'password' => $request->senha,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
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
            
            return redirect()->route('dashboard');
        }

        throw ValidationException::withMessages([
            'usuario' => ['As credenciais fornecidas estão incorretas.'],
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

