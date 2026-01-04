<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ServiceMasterController extends Controller
{
    //
    public function index()
    {
        return view('backend.pages.master.service');
    }

    public function service_get(Request $request)
    {
        try {
            $search = $request->input('search');
            $query = DB::table('service_master');

            if (!empty($search)) {
                $query->where('service_type', 'LIKE', "%{$search}%");
            }

            $data = $query->orderBy('id', 'desc')->paginate(5);

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
public function service_edit($id)
{
    try {
        $data = DB::table('service_master')->where('id', $id)->first();
        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'Service not found']);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}
    public function service_store(Request $request)
    {
        $request->validate([
            'service_type' => 'required|string|max:255',
        ]);

        DB::table('service_master')->insert([
            'unique_id' => uniqid('SRV_'),
            'service_type' => $request->service_type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Service added successfully!']);
    }

 
    public function update(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'service_type' => 'required|string|max:255'
            ]);

            DB::table('service_master')
                ->where('id', $request->id)
                ->update([
                    'service_type' => $request->service_type,
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Service updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()]);
        }
    }

    public function service_delete($id)
    {
        DB::table('service_master')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Service deleted successfully!']);
    }
}
