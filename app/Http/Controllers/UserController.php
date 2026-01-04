<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    //

    public function users()
    {
        $roles= DB::table('roles')->get();
           $users = DB::table('users')
        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->select('users.*', 'roles.name as role_name')
        ->get();


        return view('backend.pages.master.users',compact('roles','users'));
    }
     

public function addnew_user(Request $request)
{
    try {
        // ✅ Validate Input
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|exists:roles,id',
            'status'   => 'required|in:Active,Inactive',
        ]);

        $name = $request->name;
        $email = $request->email;
        $password = Hash::make($request->password);
        $email_verified_at = now();
        $remember_token = Str::random(10);
        $created_at = now();
        $updated_at = now();

        // ✅ Raw insert into users table
        DB::insert(
            'INSERT INTO users (name, email, email_verified_at, password, remember_token, created_at, updated_at, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [$name, $email, $email_verified_at, $password, $remember_token, $created_at, $updated_at, $request->status]
        );

        // ✅ Get inserted user
        $user = DB::table('users')->where('email', $email)->first();

        // ✅ Assign role manually in model_has_roles (Spatie)
        DB::table('model_has_roles')->insert([
            'role_id'    => $request->role,
            'model_type' => 'App\Models\User',
            'model_id'   => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User added successfully!'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // 🔥 Return validation errors
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors'  => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        // 🔥 Return actual exception error
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}






public function updateUser(Request $request, $id)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email',
        'role'  => 'required|exists:roles,id',
        'status'=> 'required|in:Active,Inactive',
    ]);

    // 🔹 Update user info (Raw DB)
    DB::table('users')
        ->where('id', $id)
        ->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

    // 🔹 Update role in Spatie tables
    // पहले पुराने roles हटाएँ
    DB::table('model_has_roles')->where('model_id', $id)->delete();

    // नया role assign करें
    DB::table('model_has_roles')->insert([
        'role_id' => $request->role,
        'model_type' => 'App\Models\User',
        'model_id' => $id,
    ]);

    return redirect()->back()->with('success', 'User updated successfully!');
}





public function deleteUser($id)
{
    try {
        DB::beginTransaction();

        // 🔹 पहले Spatie के model_has_roles टेबल से roles हटाएँ
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        // 🔹 फिर users टेबल से user delete करें
        DB::table('users')->where('id', $id)->delete();

        DB::commit();

        return redirect()->back()->with('success', 'User deleted successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
    }
}







}
