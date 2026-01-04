<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GarageController extends Controller
{
    public function index()
    {
        return view('backend.pages.master.garage');
    }

    public function add_new_garage(Request $request)
    {
        try {
            $unique_id = time() . rand(1000, 9999);
            DB::table('garage_master')->insert([
                'unique_id' => $unique_id,
                'name' => $request->garage_name,
                'location' => $request->location,
                'mobile' => $request->mobile,
                'mail' => $request->email,
                'manager' => $request->manager,
                'status' => $request->status,
                'navigation' => $request->navigation,
            ]);
            return response()->json(['success' => true, 'message' => 'Garage added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Garage addition failed: ' . $e->getMessage()]);
        }
    }

    public function garage_get(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('garage_master');
        if (!empty($search)) {
            $query->where('name', 'LIKE', "%{$search}%");
        }
        $garage_data = $query->orderBy('id', 'desc')->paginate(5);
        return response()->json(['success' => true, 'data' => $garage_data]);
    }

    public function edit($id)
    {
        $data = DB::table('garage_master')->where('id', $id)->first();
        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        }
        return response()->json(['success' => false, 'message' => 'Garage not found']);
    }

    public function update(Request $request)
    {
        try {
            DB::table('garage_master')->where('id', $request->garage_id)->update([
                'name' => $request->garage_name,
                'location' => $request->location,
                'mobile' => $request->mobile,
                'mail' => $request->email,
                'manager' => $request->manager,
                'status' => $request->status,
                'navigation' => $request->navigation,
            ]);
            return response()->json(['success' => true, 'message' => 'Garage updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        DB::table('garage_master')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Garage deleted successfully!']);
    }
}
