<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialBankingController extends Controller
{
    public function index()
    {
        return view('backend.pages.financial_banking.index');
    }

    public function get()
    {
        $data = DB::select("SELECT * FROM financial_banking ORDER BY id DESC");

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function save(Request $r)
    {
        $id = $r->id;
        $path = null;

        // QR upload
        if ($r->hasFile('qr_code')) {
            $file = $r->file('qr_code');
            $fileName = time().'_qr_'.uniqid().'.'.$file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/qr_codes');
            $file->move($uploadPath, $fileName);
            $path = 'uploads/qr_codes/'.$fileName;
        }

        // UPDATE
        if ($id) {

            if ($path) {
                DB::update("
                    UPDATE financial_banking
                    SET bank_name=?, account_holder=?, ifsc=?, account_number=?, upi_id=?, qr_code=?
                    WHERE id=?
                ", [
                    $r->bank_name,
                    $r->account_holder,
                    $r->ifsc,
                    $r->account_number,
                    $r->upi_id,
                    $path,
                    $id
                ]);
            } else {
                DB::update("
                    UPDATE financial_banking
                    SET bank_name=?, account_holder=?, ifsc=?, account_number=?, upi_id=?
                    WHERE id=?
                ", [
                    $r->bank_name,
                    $r->account_holder,
                    $r->ifsc,
                    $r->account_number,
                    $r->upi_id,
                    $id
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully'
            ]);
        }

        // INSERT
        DB::insert("
            INSERT INTO financial_banking (bank_name, account_holder, ifsc, account_number, upi_id, qr_code)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            $r->bank_name,
            $r->account_holder,
            $r->ifsc,
            $r->account_number,
            $r->upi_id,
            $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Saved successfully'
        ]);
    }

    public function edit($id)
    {
        $row = DB::selectOne("SELECT * FROM financial_banking WHERE id = ?", [$id]);

        if (!$row) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Record not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $row
        ]);
    }

    public function delete($id)
    {
        DB::delete("DELETE FROM financial_banking WHERE id = ?", [$id]);

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
