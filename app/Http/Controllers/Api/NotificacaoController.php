<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller
{
    public function index()
    {
        $notificacoes = Auth::user()->notificacoes()
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notificacoes,
        ]);
    }

    public function marcarComoLida(Notificacao $notificacao)
    {
        if ($notificacao->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado',
            ], 403);
        }

        $notificacao->marcarComoLida();

        return response()->json([
            'success' => true,
            'message' => 'Notificação marcada como lida',
        ]);
    }
}





