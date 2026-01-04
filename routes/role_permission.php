<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;



// Route::middleware(['custom_auth', 'role:admin'])->group(function () {

    Route::get('/roles', [PermissionController::class, 'roles'])->name('roles');
    Route::get('/permission', [PermissionController::class, 'permission'])->name('permission');

    //  Route::post('/add_new_permission', [PermissionController::class, 'add_new_permission'])->name('add_new_permission');
Route::get('/permissions_get', [PermissionController::class, 'getPermissions'])->name('permissions_get');
    Route::post('/add_permission', [PermissionController::class, 'addPermission'])->name('add_permission');
// Route::post('/permissions_update', [PermissionController::class, 'updatePermission'])->name('permissions_update');
Route::delete('/delete_permission/{id}', [PermissionController::class, 'deletePermission'])->name('delete_permission');


Route::post('/permissions_update', [PermissionController::class, 'updatePermission'])->name('permissions_update');
// roles part
Route::post('/add_new_role', [PermissionController::class, 'add_new_role'])->name('add_new_role');
Route::get('/roles_data_get', [PermissionController::class, 'getroles_data'])->name('roles_data_get');

Route::put('update_role/{id}', [PermissionController::class, 'updateRole']);
Route::delete('/delete_role/{id}', [PermissionController::class, 'deleteRole']);






// }); 