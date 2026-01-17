<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;
 


Route::middleware(['custom_auth', 'role:Admin'])->group(function () {

Route::get('/my_leads', [LeadController::class, 'my_leads'])->name('my_leads');
Route::get('/lead_generation', [LeadController::class, 'lead_generation'])->name('lead_generation');
Route::post('/save_client_data', [LeadController::class, 'save_client_data'])->name('save_client_data');
Route::post('/assign_lead_to_manager', [LeadController::class, 'assign_lead_to_manager'])->name('assign_lead_to_manager');
  Route::post('/save_client_car_filter', [LeadController::class, 'save_client_car_filter'])->name('save_client_car_filter');
  
    Route::post('/share_available_cars', [LeadController::class, 'share_available_cars'])->name('share_available_cars');
    
  Route::get('/car-share/{token}', [LeadController::class, 'publicCars']);
Route::post('/car-share/{token}/confirm', [LeadController::class, 'confirmCar']);

  
  
  Route::post('/share_available_cars', [LeadController::class, 'share_available_cars']);
Route::post('/delete_car_share_token', [LeadController::class, 'delete_car_share_token']);

   
// Backend Update
Route::post('/save_client_data_from_client', [LeadController::class, 'save_client_data_from_client']);


});  