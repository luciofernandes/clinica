<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function graficoAnual(Request $request)
    {
        abort_unless(Auth::user()?->is_admin, 403);

        $ano = $request->input('ano', now()->year);

        $faturado = DB::table('billings')
            ->select(DB::raw(' mes'), DB::raw('SUM(valor_mes) as total'))
            ->where('ano', $ano)
            ->groupBy('mes')
            ->pluck('total', 'mes');

        if ($request->input('tipo_data') === 'referencia') {
            $recebido = DB::table('receipts')
                ->select(DB::raw('mes_ref'), DB::raw('SUM(valor_pagorec) as total'))
                ->where('ano_ref', $ano)
                ->groupBy('mes_ref')
                ->pluck('total', 'mes_ref');
        } else {

            $recebido = DB::table('receipts')
                ->select(DB::raw('mes'), DB::raw('SUM(valor_pagorec) as total'))
                ->where('ano', $ano)
                ->groupBy('mes')
                ->pluck('total', 'mes');
        }

        $meses = collect(range(1, 12));


        $dados = $meses->map(function ($mes) use ($faturado, $recebido) {
            $f = floatval($faturado[$mes] ?? 0);
            $r = floatval($recebido[$mes] ?? 0);
            return [
                'mes' => $mes,
                'faturado' => $f,
                'recebido' => $r,
                'diferenca' => abs($f - $r),
                'cor' => $r >= $f ? 'blue' : 'red',
            ];
        });

        return view('financeiro.grafico', [
            'dados' => $dados,
            'faturadoArray' => $dados->pluck('faturado')->values()->all(),
            'recebidoArray' => $dados->pluck('recebido')->values()->all(),
            'diferencaArray' => $dados->pluck('diferenca')->values()->all(),
            'coresDiferenca' => $dados->pluck('cor')->values()->all(),
            'mesesArray' => $dados->pluck('mes')->values()->all(),
            'ano' => $ano,
        ]);


    }
}
