<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\BillingImportController;
use App\Http\Controllers\BillingSummaryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientImportController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;


//Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

//Route::get('/register', function () {
//    abort(403, 'Registro desativado.');
//});

Route::middleware('auth')->group(function () {

    // 🏠 Dashboard e início
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);


    // 👤 Pacientes
    Route::get('/pacientes/importar', [PatientImportController::class, 'index'])->name('pacientes.importar');
    Route::post('/pacientes/importar', [PatientImportController::class, 'store'])->name('pacientes.importar.salvar');
    Route::get('/pacientes', [PatientController::class, 'index'])->name('pacientes.index');
    Route::get('/pacientes/novo', [PatientController::class, 'create'])->name('pacientes.create');
    Route::post('/pacientes', [PatientController::class, 'store'])->name('pacientes.store');
    Route::delete('/pacientes/deletar', [PatientController::class, 'delete'])->name('pacientes.deletar');

    // 🗂️ Autorizações
    Route::resource('autorizacoes', AuthorizationController::class);

    // 💸 Cobranças e Notas Fiscais
    Route::get('/autorizacoes/{authorization}/cobrancas', [InvoiceController::class, 'index'])->name('cobrancas.index');
    Route::post('/autorizacoes/{authorization}/cobrancas', [InvoiceController::class, 'store'])->name('cobrancas.store');
    Route::put('/cobrancas/{invoice}', [InvoiceController::class, 'update'])->name('cobrancas.update');
    Route::delete('/cobrancas/{invoice}', [InvoiceController::class, 'destroy'])->name('cobrancas.destroy');
    Route::get('/cobrancas/autorizar', [\App\Http\Controllers\AuthorizationController::class, 'listarParaCobranca'])->name('cobrancas.autorizar');
    Route::get('/cobrancas/{invoice}/editar', [InvoiceController::class, 'edit'])->name('cobrancas.edit');
    Route::put('/cobrancas/{invoice}', [InvoiceController::class, 'update'])->name('cobrancas.update');
    Route::delete('autorizacoes/{authorization}/modalidades/{modality}', [AuthorizationController::class, 'removeModality'])
        ->name('autorizacoes.modalidades.destroy');

    // 📊 Relatórios e Dashboards
    Route::get('/cobrancas/resumo', [BillingSummaryController::class, 'index'])->name('cobrancas.resumo');
    Route::get('/dashboard/financeiro', [DashboardController::class, 'financeiro'])->name('dashboard.financeiro');

    Route::get('/usuarios', [\App\Http\Controllers\UserController::class, 'index'])->name('user.index');
    Route::get('/usuarios/create', [\App\Http\Controllers\UserController::class, 'create'])->name('user.create');
    Route::post('/usuarios ', [\App\Http\Controllers\UserController::class, 'store'])->name('user.store');
    Route::delete('/usuarios/{user}/destroy', [\App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');


    Route::get('/billing/import', [BillingImportController::class, 'showForm'])->name('billing.form');
    Route::post('/billing/import', [BillingImportController::class, 'import'])->name('billing.import');


    Route::get('/receipt/import', [ReceiptController::class, 'showForm'])->name('receipt.form');
    Route::post('/receipt/import', [ReceiptController::class, 'import'])->name('receipt.import');

    Route::get('/financeiro/grafico', [FinanceController::class, 'graficoAnual'])->name('financeiro.grafico');
    Route::get('/financeiro/resumo', [\App\Http\Controllers\FinanceController::class, 'resumoMensal'])->name('financeiro.resumo');


    Route::get('/comissoes/importar', [\App\Http\Controllers\CommissionController::class, 'showForm']  )->name('comissoes.form');
    Route::post('/comissoes/importar', [\App\Http\Controllers\CommissionController::class, 'importarComissoes']  )->name('comissoes.import');

});
