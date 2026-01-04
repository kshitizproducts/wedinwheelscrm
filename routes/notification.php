<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\NotificationController;


// Route::middleware(['custom_auth', 'role:admin'])->group(function () {

    Route::get('/add_notification', [NotificationController::class, 'add_notification'])->name('add_notification');
   Route::post('/new_notification', [NotificationController::class, 'new_notification'])->name('new_notification');
  




// }); 