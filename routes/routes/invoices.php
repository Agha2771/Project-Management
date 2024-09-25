<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;


Route::prefix('invoices')->group(function () {
    Route::get('/{client?}', [InvoiceController::class, 'getInvoices']);
    Route::get('/invoice/{invoice}', [InvoiceController::class, 'getInvoice']);
    Route::get('/delete/{InvId}', [InvoiceController::class, 'destroy']);
    Route::post('/create', [InvoiceController::class, 'create']);
    Route::post('/attachmets', [InvoiceController::class, 'storeAttachments']);
    Route::put('/update/{projId}', [InvoiceController::class, 'update']);
    Route::post('/{invoice}/send', [InvoiceController::class, 'sendInvoice']);
    Route::post('/{invoice}/pdf', [InvoiceController::class, 'generatePDF']);
});

