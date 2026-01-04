<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ServiceMasterController;


// Route::middleware(['custom_auth', 'role:admin'])->group(function () {

//     Route::get('/garage', [GarageController::class, 'garage'])->name('garage');
//    Route::post('/add_new_garage', [GarageController::class, 'add_new_garage'])->name('add_new_garage');
//   Route::get('/garage_get', [GarageController::class, 'garage_get'])->name('garage_get');

Route::get('/service', [ServiceMasterController::class, 'index'])->name('service');
Route::get('/service_get', [ServiceMasterController::class, 'service_get'])->name('service_get');
Route::post('/service_store', [ServiceMasterController::class, 'service_store'])->name('service_store');
Route::post('/service_update', [ServiceMasterController::class, 'update'])->name('service_update');
Route::get('/service/edit/{id}', [ServiceMasterController::class, 'service_edit']);
Route::delete('/service/delete/{id}', [ServiceMasterController::class, 'service_delete'])->name('service_delete');
Route::put('/service/update/{id}', [ServiceMasterController::class, 'update'])->name('service_update');


// }); 