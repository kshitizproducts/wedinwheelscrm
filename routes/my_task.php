<?php
use App\Http\Controllers\DriverTaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MyTaskController;



Route::middleware(['custom_auth', 'role:Admin'])->group(function () {

// Route::get('/roles', [PermissionController::class, 'roles'])->name('roles');
// Route::get('/permission', [PermissionController::class, 'permission'])->name('permission');

//  Route::post('/add_new_permission', [PermissionController::class, 'add_new_permission'])->name('add_new_permission');
 


Route::get('/my_task', [MyTaskController::class, 'my_task'])->name('my_task');
Route::get('/driver_schedule', [MyTaskController::class, 'driver_schedule'])->name('driver_schedule');


  Route::post('assign_task_to_drivers', [DriverTaskController::class, 'assign_task_to_drivers'])
    ->name('assign_task_to_drivers');


// Route::post('/add_new_cars', [CarsMasterController::class, 'add_new_cars'])->name('add_new_cars');
// Route::post('/update_new_cars', [CarsMasterController::class, 'update_new_cars'])->name('update_new_cars');
// Route::post('/delete_new_cars', [CarsMasterController::class, 'delete_new_cars'])->name('delete_new_cars');

 
// Route::get('/update_car_profile/{id}', [CarsMasterController::class, 'update_car_profile'])->name('update_car_profile');


// Route::post('/update_profile_part1', [CarsMasterController::class, 'updateProfilePart1'])->name('update_profile_part1');
// Route::post('/update_profile_part_2', [CarsMasterController::class, 'updateProfilePart2'])->name('update_profile_part_2');
// Route::post('/add_document', [CarsMasterController::class, 'add_document'])->name('add_document');
// Route::post('/update_document', [CarsMasterController::class, 'update_document'])->name('update_document');
// Route::post('/delete_document_car', [CarsMasterController::class, 'delete_document_car'])->name('delete_document_car');
// Route::post('/add_servicing', [CarsMasterController::class, 'add_servicing'])->name('add_servicing');
// Route::post('/update_servicing', [CarsMasterController::class, 'update_servicing'])->name('update_servicing');
// Route::post('/delete_btn_servicing', [CarsMasterController::class, 'delete_btn_servicing'])->name('delete_btn_servicing');
// Route::post('/additional_info_car', [CarsMasterController::class, 'additional_info_car'])->name('additional_info_car');
// Route::post('/save_imp_docs', [CarsMasterController::class, 'save_imp_docs'])->name('save_imp_docs');



}); 