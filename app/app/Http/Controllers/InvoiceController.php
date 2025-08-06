<?php

namespace App\Http\Controllers;

use App\Models\Authorization;
use App\Models\Invoice;
use Illuminate\Http\Request;


class InvoiceController extends Controller
{

    public function index(Authorization $authorization)
    {
        $invoices = $authorization->invoices()->orderByDesc('issue_date')->get();
        return view('invoices.index', compact('authorization', 'invoices'));
    }

    public function store(Request $request, Authorization $authorization)
    {

        $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'amount' => 'required|numeric',
            'issue_date' => 'required|date',
            'authorization_modality_ids' => 'required|array|min:1',
        ]);

        $invoice = $authorization->invoices()->create([
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'status' => 'pendente',
            'issue_date' => $request->issue_date,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $invoice->authorizationModalities()->sync($request->authorization_modality_ids);


        return back()->with('status', 'Nota fiscal adicionada com sucesso.');
    }


    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return back()->with('status', 'Nota fiscal removida.');
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $rules = [
            'status' => 'required|in:pendente,enviado,pago,recebido',
        ];

        if ($request->status === 'pago') {
            $rules['payment_date'] = 'required|date';
        }

        $request->validate($rules);

        $invoice->update([
            'status' => $request->status,
            'payment_date' => $request->status === 'pago' ? $request->payment_date : null,
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('cobrancas.index', $invoice->authorization_id)
            ->with('status', 'Cobrança atualizada com sucesso.');
    }

}
