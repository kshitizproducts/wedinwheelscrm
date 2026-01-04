<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\GarageController;


// Route::middleware(['custom_auth', 'role:admin'])->group(function () {

//     Route::get('/garage', [GarageController::class, 'garage'])->name('garage');
//    Route::post('/add_new_garage', [GarageController::class, 'add_new_garage'])->name('add_new_garage');
//   Route::get('/garage_get', [GarageController::class, 'garage_get'])->name('garage_get');

Route::get('garage', [GarageController::class, 'index'])->name('garage');
Route::get('garage_get', [GarageController::class, 'garage_get'])->name('garage_get');
Route::post('add_new_garage', [GarageController::class, 'add_new_garage'])->name('add_new_garage');
Route::get('garage/edit/{id}', [GarageController::class, 'edit'])->name('garage_edit');
Route::post('garage/update', [GarageController::class, 'update'])->name('garage_update');
Route::delete('garage/delete/{id}', [GarageController::class, 'delete'])->name('garage_delete');
// }); 