<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountsController;



Route::middleware(['custom_auth', 'role:Admin'])->group(function () {



Route::get('/accounts', [AccountsController::class, 'accounts'])->name('accounts');
    Route::get('accounts/get', [AccountsController::class, 'get'])->name('accounts.get');
    Route::post('accounts', [AccountsController::class, 'store'])->name('accounts.store');
    // If you want separate update route, you can add:
    // Route::post('accounts/update', [AccountsController::class, 'store'])->name('accounts.update');

    Route::get('accounts/{id}/edit', [AccountsController::class, 'edit'])->name('accounts.edit');
    Route::delete('accounts/{id}', [AccountsController::class, 'destroy'])->name('accounts.destroy');

}); 