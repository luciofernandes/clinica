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
            'status' => 'required|in:pendente,enviado,pago,recebido',
            'issue_date' => 'required|date',
        ]);

        $authorization->invoices()->create([
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'status' => $request->status,
            'issue_date' => $request->issue_date,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return back()->with('status', 'Nota fiscal adicionada com sucesso.');
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:pendente,enviado,pago,recebido',
            'amount' => 'nullable|numeric',
        ]);

        $invoice->update([
            'status' => $request->status,
            'amount' => $request->amount ?? $invoice->amount,
            'updated_by' => auth()->id(),
        ]);

        return back()->with('status', 'Cobrança atualizada.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return back()->with('status', 'Nota fiscal removida.');
    }
}
