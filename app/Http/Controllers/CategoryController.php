<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index()
    {
        // Fetch categories ordered by latest
        $categories = DB::table('product_category_master')->orderBy('id', 'desc')->get();
        return view('backend.pages.master.category_master', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ]);

        try {
            $id = $request->id;
            $data = [
                'name' => $request->name,
                'code' => $request->code,
                'updated_at' => Carbon::now(),
            ];

            if ($id) {
                // Update existing category
                DB::table('product_category_master')->where('id', $id)->update($data);
                $message = "Category updated successfully!";
            } else {
                // Insert new category
                $data['created_at'] = Carbon::now();
                DB::table('product_category_master')->insert($data);
                $message = "Category added successfully!";
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::table('product_category_master')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete category.'], 500);
        }
    }
}