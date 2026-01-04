<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;



Route::middleware(['custom_auth', 'role:Admin'])->group(function () {

Route::get('/bookings', [BookingController::class, 'bookings'])->name('bookings');
// Route::get('/permission', [PermissionController::class, 'permission'])->name('permission');

//  Route::post('/add_new_permission', [PermissionController::class, 'add_new_permission'])->name('add_new_permission');
 
Route::get('/calender_and_schedule', [BookingController::class, 'calender_and_schedule'])->name('calender_and_schedule');



}); 