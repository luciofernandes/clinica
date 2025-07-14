<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\BillingSummaryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientImportController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

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

    // 🗂️ Autorizações
    Route::resource('autorizacoes', AuthorizationController::class);

    // 💸 Cobranças e Notas Fiscais
    Route::get('/autorizacoes/{authorization}/cobrancas', [InvoiceController::class, 'index'])->name('cobrancas.index');
    Route::post('/autorizacoes/{authorization}/cobrancas', [InvoiceController::class, 'store'])->name('cobrancas.store');
    Route::put('/cobrancas/{invoice}', [InvoiceController::class, 'update'])->name('cobrancas.update');
    Route::delete('/cobrancas/{invoice}', [InvoiceController::class, 'destroy'])->name('cobrancas.destroy');
    Route::get('/cobrancas/autorizar', [\App\Http\Controllers\AuthorizationController::class, 'listarParaCobranca'])->name('cobrancas.autorizar');

    // 📊 Relatórios e Dashboards
    Route::get('/cobrancas/resumo', [BillingSummaryController::class, 'index'])->name('cobrancas.resumo');
    Route::get('/dashboard/financeiro', [DashboardController::class, 'financeiro'])->name('dashboard.financeiro');
});
