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

    public function resumoMensal(Request $request)
    {
        abort_unless(auth()->user()?->is_admin, 403);
        $ano = $request->input('ano', now()->year);

        // Tabela billings
        $faturamento = DB::table('billings')
            ->selectRaw('ano, mes, SUM(valor_mes) as total_faturado')
            ->where('ano', $ano)
            ->groupBy('ano', 'mes');

        // Tabela receipts
        $recebimentos = DB::table('receipts')
            ->selectRaw('ano, mes, SUM(valor_pagorec) as total_recebido')
            ->where('ano', $ano)
            ->groupBy('ano', 'mes');

        // Tabela commissions
        $comissoes = DB::table('commissions')
            ->selectRaw('ano, mes, SUM(valor_comissao) as total_comissao')
            ->where('ano', $ano)
            ->groupBy('ano', 'mes');

        // Unir os dados via subquery
        $dados = DB::table(DB::raw("({$faturamento->toSql()}) as faturamento"))
            ->mergeBindings($faturamento)
            ->leftJoinSub($recebimentos, 'recebimentos', function ($join) {
                $join->on('faturamento.ano', '=', 'recebimentos.ano')
                    ->on('faturamento.mes', '=', 'recebimentos.mes');
            })
            ->leftJoinSub($comissoes, 'comissoes', function ($join) {
                $join->on('faturamento.ano', '=', 'comissoes.ano')
                    ->on('faturamento.mes', '=', 'comissoes.mes');
            })
            ->select(
                'faturamento.ano',
                'faturamento.mes',
                DB::raw('COALESCE(faturamento.total_faturado, 0) as total_faturado'),
                DB::raw('COALESCE(recebimentos.total_recebido, 0) as total_recebido'),
                DB::raw('COALESCE(comissoes.total_comissao, 0) as total_comissao')
            )
            ->orderByDesc('faturamento.ano')
            ->orderByDesc('faturamento.mes')
            ->get();

        return view('financeiro.resumo', compact('dados'));
    }

}
