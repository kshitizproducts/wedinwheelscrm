<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;



Route::middleware(['custom_auth', 'role:Admin'])->group(function () {

Route::get('/my_leads', [LeadController::class, 'my_leads'])->name('my_leads');
Route::get('/lead_generation', [LeadController::class, 'lead_generation'])->name('lead_generation');
Route::post('/save_client_data', [LeadController::class, 'save_client_data'])->name('save_client_data');
Route::post('/assign_lead_to_manager', [LeadController::class, 'assign_lead_to_manager'])->name('assign_lead_to_manager');
  
  
  
  
  
  
  
// Backend Update
Route::post('/save_client_data_from_client', [LeadController::class, 'save_client_data_from_client']);

// Public Showcase (Bina login wala)
Route::get('/showcase/{token}', [LeadController::class, 'publicShowcase'])->name('public.showcase');
Route::post('/finalize-car', [LeadController::class, 'finalizeCar']);
});  