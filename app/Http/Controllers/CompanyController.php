<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = DB::table('company_list')->orderBy('id', 'desc')->get();
        return view('backend.pages.master.company_master', compact('companies'));
    }

    public function store(Request $request)
    {
        // Validation with custom messages
        $request->validate([
            'company_name' => 'required|string|max:255',
        ]);

        try {
            $id = $request->id;
            $data = [
                'company_name' => $request->company_name,
                'updated_at'   => Carbon::now(),
            ];

            if ($id) {
                // Raw DB Update
                DB::table('company_list')->where('id', $id)->update($data);
                return response()->json(['success' => true, 'message' => 'Company updated successfully!']);
            } else {
                // Raw DB Insert
                $data['created_at'] = Carbon::now();
                DB::table('company_list')->insert($data);
                return response()->json(['success' => true, 'message' => 'Company added successfully!']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::table('company_list')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Company deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Delete failed!'], 500);
        }
    }
}