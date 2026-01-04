<?php

namespace App\Http\Controllers;
 use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    //

public function roles()
{
    // Fetch all permissions
    $permissions = DB::table('permissions')->get();

    // Fetch all roles
    $roles = DB::table('roles')->get();

    // Group permissions by the 2nd word in their name (e.g., "create cars" → group "cars")
    $groupedPermissions = $permissions->groupBy(function ($perm) {
        $parts = explode(' ', $perm->name);
        return $parts[1] ?? 'Other';
    });

    // Build a map: role_id => [permission_id, permission_id, ...]
    $rolePermissions = DB::table('role_has_permissions')
        ->whereIn('role_id', $roles->pluck('id'))
        ->get()
        ->groupBy('role_id')
        ->map(function ($rows) {
            return $rows->pluck('permission_id')->toArray();
        })
        ->toArray();

    // Pass everything to the view
    return view('backend.pages.master.role_permission.roles', compact(
        'permissions',
        'roles',
        'groupedPermissions',
        'rolePermissions'
    ));
}


    public function permission()
    {
         return view('backend.pages.master.role_permission.permissions.permission');
    }


    // public function add_new_permission(Request $request)
    // {
    //     // dd($request->all());
    //     $permissionName= $request->permissionName;
    //     $inert= DB::table('permissions')->insert([
    //         'name'=>$permissionName,
    //         'guard_name'=>'web'
    //     ]);

    //     if($inert)
    //     {
    //          return response()->json(['success' => true, 'message' => 'Permission Added Succssfully!!!']);
    //     }
    //     else{
    //          return response()->json(['success' => false, 'message' => 'Permission Added Failed!!!']);
    //     }

       
    // }
public function addPermission(Request $request)
{
    try {
        $validated = $request->validate([
            'permissionName' => 'required|string|max:255',
        ]);

        // ✅ Correct table name (Spatie uses plural "permissions")
        $inserted = DB::table('permissions')->insert([
            'name' => $validated['permissionName'],
            'guard_name' => 'web', // ⚠️ required for Spatie Permission
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => $inserted,
            'message' => $inserted 
                ? 'Permission added successfully!' 
                : 'Failed to add permission!',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ]);
    }
}

public function getPermissions(Request $request)
{
    try {
        $search = $request->input('search');
        $query = DB::table('permissions');

        if (!empty($search)) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $permissions = $query->orderBy('id', 'desc')->paginate(5); // 5 per page

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch permissions: ' . $e->getMessage()
        ]);
    }
}







    //   public function getPermissions()
    // {
    //     $permissions = DB::table('permissions')->select('id', 'name')->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $permissions
    //     ]);
    // }

    //  public function updatePermission(Request $request)
    // {
    //     $update = DB::table('permissions')
    //         ->where('id', $request->id)
    //         ->update(['name' => $request->permissionName]);

    //     return $update
    //         ? response()->json(['success' => true, 'message' => 'Permission Updated Successfully!'])
    //         : response()->json(['success' => false, 'message' => 'Update Failed!']);
    // }

    public function updatePermission(Request $request)
{
    if (!$request->id) {
        return response()->json(['success' => false, 'message' => 'Invalid ID']);
    }

    $update = DB::table('permissions')
        ->where('id', $request->id)
        ->update(['name' => $request->permissionName, 'updated_at' => now()]);

    return $update
        ? response()->json(['success' => true, 'message' => 'Permission Updated Successfully!'])
        : response()->json(['success' => false, 'message' => 'Update Failed!']);
}


    public function deletePermission($id)
    {
        $delete = DB::table('permissions')->where('id', $id)->delete();

        return $delete
            ? response()->json(['success' => true, 'message' => 'Permission Deleted Successfully!'])
            : response()->json(['success' => false, 'message' => 'Delete Failed!']);
    }





 public function add_new_role(Request $request)
    {
        try {
            $validated = $request->validate([
                'role_name' => 'required|string|max:255',
                'permissions' => 'required|array|min:1',
                'permissions.*' => 'integer|exists:permissions,id'
            ]);

            // Create role and get ID
            $current_role_id = DB::table('roles')->insertGetId([
                'name' => $request->role_name,
                'guard_name' => 'web'
            ]);

            // Prepare permissions array for bulk insert
            $rolePermissions = [];
            foreach ($request->permissions as $permission) {
                $rolePermissions[] = [
                    'role_id' => $current_role_id,
                    'permission_id' => $permission
                ];
            }

            // Insert role-permissions
            DB::table('role_has_permissions')->insert($rolePermissions);

            return response()->json([
                'success' => true,
                'message' => 'Role and permissions added successfully!',
                'role_id' => $current_role_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }




    public function updateRole(Request $request, $id)
{
    $request->validate([
        'role_name' => 'required|string|max:255',
        'permissions' => 'required|array|min:1',
    ]);

    // Update role name
    DB::table('roles')->where('id', $id)->update([
        'name' => $request->role_name,
        'updated_at' => now(),
    ]);

    // Sync permissions
    DB::table('role_has_permissions')->where('role_id', $id)->delete();
    $permissionsData = [];
    foreach ($request->permissions as $permId) {
        $permissionsData[] = ['role_id' => $id, 'permission_id' => $permId];
    }
    DB::table('role_has_permissions')->insert($permissionsData);

    return response()->json([
        'success' => true,
        'message' => 'Role updated successfully!'
    ]);
}





public function deleteRole($id)
{
    try {
        // Delete permissions assigned to role first
        DB::table('role_has_permissions')->where('role_id', $id)->delete();

        // Delete the role
        DB::table('roles')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

// roles section starts here



}
