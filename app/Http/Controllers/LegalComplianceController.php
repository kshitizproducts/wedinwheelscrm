<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LegalComplianceController extends Controller
{
    public function index()
    {
        return view('backend.pages.legal.index');
    }

    public function get()
    {
        $data = DB::table('legal_documents')->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function save(Request $r)
    {
        $id = $r->id;

        $existing = null;
        if ($id) {
            $existing = DB::table('legal_documents')->find($id);
        }

        $data = [
            'gst_number'          => $r->gst_number,
            'gst_expiry'          => $r->gst_expiry,
            'pan_number'          => $r->pan_number,
            'pan_expiry'          => $r->pan_expiry,
            'trade_number'        => $r->trade_number,
            'trade_expiry'        => $r->trade_expiry,
            'msme_number'         => $r->msme_number,
            'msme_expiry'         => $r->msme_expiry,
            'rent_agreement_expiry' => $r->rent_agreement_expiry,
            'electricity_bill_expiry' => $r->electricity_bill_expiry,
        ];

        $uploadPath = public_path('uploads/legal');

        // Helper for MOVE upload
        function uploadDoc($file, $uploadPath)
        {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            return 'uploads/legal/' . $filename;
        }

        if ($r->hasFile('gst_file')) {
            $data['gst_file'] = uploadDoc($r->file('gst_file'), $uploadPath);
        }

        if ($r->hasFile('pan_file')) {
            $data['pan_file'] = uploadDoc($r->file('pan_file'), $uploadPath);
        }

        if ($r->hasFile('trade_file')) {
            $data['trade_file'] = uploadDoc($r->file('trade_file'), $uploadPath);
        }

        if ($r->hasFile('msme_file')) {
            $data['msme_file'] = uploadDoc($r->file('msme_file'), $uploadPath);
        }

        if ($r->hasFile('rent_agreement_file')) {
            $data['rent_agreement_file'] = uploadDoc($r->file('rent_agreement_file'), $uploadPath);
        }

        if ($r->hasFile('electricity_bill_file')) {
            $data['electricity_bill_file'] = uploadDoc($r->file('electricity_bill_file'), $uploadPath);
        }

        if ($id) {
            DB::table('legal_documents')->where('id', $id)->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully'
            ]);
        }

        DB::table('legal_documents')->insert($data);

        return response()->json([
            'success' => true,
            'message' => 'Saved successfully'
        ]);
    }

 public function edit($id)
{
    $row = DB::table('legal_documents')->find($id);

    return response()->json([
        'success' => true,
        'data' => $row
    ]);
}


    public function delete($id)
    {
        DB::table('legal_documents')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
