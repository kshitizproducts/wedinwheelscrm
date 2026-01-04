<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;



Route::middleware(['custom_auth', 'role:Admin'])->group(function () {


    // only routs codes

    // Route::get('/users', function () {
    //     return view('backend.pages.master.users');
    // });


    // only route with controller codes
 


    Route::get('/users', [UserController::class, 'users'])->name('users');
    // Route::get('/permissions.get', [UserController::class, 'getPermissions'])->name('permissions.get');
    Route::post('/addnew_user', [UserController::class, 'addnew_user'])->name('addnew_user');
    Route::put('/update_user/{id}', [UserController::class, 'updateUser'])->name('update_user');
    Route::delete('/delete_user/{id}', [UserController::class, 'deleteUser'])->name('delete_user');


    // Route::post('/permissions_update', [UserController::class, 'updatePermission'])->name('permissions_update');
    // Route::delete('/delete_permission/{id}', [UserController::class, 'deletePermission'])->name('delete_permission');
// Route::get('users', [UserController::class, 'index'])->name('users.index');
// Route::get('users/list', [UserController::class, 'getUsers'])->name('users.list');
// Route::post('users/store', [UserController::class, 'store'])->name('users.store');
// Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
// Route::post('users/update/{id}', [UserController::class, 'update'])->name('users.update');
// Route::delete('users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');


});