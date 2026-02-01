<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function index()
    {
        // Join query taaki category ka naam bhi dikhe
        $items = DB::table('product_item_master as i')
            ->join('product_category_master as c', 'i.category_id', '=', 'c.id')
            ->select('i.*', 'c.name as category_name')
            ->orderBy('i.id', 'desc')
            ->get();

        // Dropdown ke liye categories fetch karein
        $categories = DB::table('product_category_master')->orderBy('name', 'asc')->get();

        return view('backend.pages.master.item_master', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'item_name'   => 'required|string|max:255',
            'item_code'   => 'required|string|max:50',
        ]);

        try {
            $id = $request->id;
            $data = [
                'category_id' => $request->category_id,
                'item_name'   => $request->item_name,
                'item_code'   => $request->item_code,
                'updated_at'  => Carbon::now(),
            ];

            if ($id) {
                DB::table('product_item_master')->where('id', $id)->update($data);
                $message = "Item updated successfully!";
            } else {
                $data['created_at'] = Carbon::now();
                DB::table('product_item_master')->insert($data);
                $message = "Item added successfully!";
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::table('product_item_master')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Item deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete item.'], 500);
        }
    }
}