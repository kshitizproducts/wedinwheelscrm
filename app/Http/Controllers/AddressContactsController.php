<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressContactsController extends Controller
{
    public function index()
    {
        return view('backend.pages.address_contacts.index');
    }

    public function get()
    {
        $data = DB::table('address_contacts')
            ->orderBy('is_head_office', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function save(Request $r)
    {
        $id = $r->id;

        $data = [
            'is_head_office'      => $r->is_head_office ? 1 : 0,
            'city'                => $r->city,
            'state'               => $r->state,
            'pincode'             => $r->pincode,
            'phone'               => $r->phone,
            'whatsapp'            => $r->whatsapp,
            'email'               => $r->email,
            'website_url'         => $r->website_url,
            'google_business_url' => $r->google_business_url,
        ];

        if ($id) {
            DB::table('address_contacts')->where('id', $id)->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully'
            ]);
        }

        DB::table('address_contacts')->insert($data);

        return response()->json([
            'success' => true,
            'message' => 'Saved successfully'
        ]);
    }

    public function edit($id)
    {
        $row = DB::table('address_contacts')->find($id);

        return response()->json([
            'success' => true,
            'data' => $row
        ]);
    }

    public function delete($id)
    {
        DB::table('address_contacts')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
