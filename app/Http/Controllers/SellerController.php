<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = DB::table('seller_masters')->orderBy('id', 'desc')->get();
        return view('backend.pages.master.seller_master', compact('sellers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seller_name'    => 'required|string|max:255',
            'seller_contact' => 'required|string|max:20',
        ]);

        try {
            $id = $request->id;
            $data = [
                'seller_name'    => $request->seller_name,
                'seller_contact' => $request->seller_contact,
                'seller_email'   => $request->seller_email,
                'seller_address' => $request->seller_address,
                'updated_at'     => Carbon::now(),
            ];

            if ($id) {
                DB::table('seller_masters')->where('id', $id)->update($data);
                $message = "Seller updated successfully!";
            } else {
                $data['created_at'] = Carbon::now();
                DB::table('seller_masters')->insert($data);
                $message = "Seller added successfully!";
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::table('seller_masters')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Seller deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete seller.'], 500);
        }
    }
}