<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;

class AccountsController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['custom_auth', 'role:Admin']);
    }

    public function accounts()
    {
        return view('backend.pages.accounts.index');
    }

    // API: get paginated accounts for JS
    public function get(Request $request)
    {
        $perPage = 10;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;

        $total = DB::table('accounts')->count();

        $accounts = DB::table('accounts')
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        $accounts = $accounts->map(function ($a) {
            return [
                'id' => $a->id,
                'bank_name' => $a->bank_name,
                'beneficiary_name' => $a->beneficiary_name,
                'masked_account_number' => substr($a->account_number_encrypted, -4), // or your masking logic
                'ifsc' => $a->ifsc_encrypted,
                'branch' => $a->branch,
                'contact' => $a->contact,
                'status' => $a->status,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'data' => $accounts
            ]
        ]);
    }

    // store new or update existing
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255',
            'beneficiary_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:64',
            'ifsc' => 'required|string|max:20',
            'branch' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'status' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $id = $request->input('account_id');

        try {
            $payload = [
                'bank_name' => $request->bank_name,
                'beneficiary_name' => $request->beneficiary_name,
                'account_number_encrypted' => Crypt::encryptString($request->account_number),
                'ifsc_encrypted' => Crypt::encryptString($request->ifsc),
                'branch' => $request->branch,
                'contact' => $request->contact,
                'status' => $request->status,
                'updated_at' => now(),
                'created_at' => now(),
            ];

            if ($id) {
                $updated = DB::table('accounts')->where('id', $id)->update($payload);
                if (!$updated) {
                    return response()->json(['success' => false, 'message' => 'Account not found']);
                }
                $message = 'Account updated successfully';
            } else {
                DB::table('accounts')->insert($payload);
                $message = 'Account added successfully';
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unable to save account']);
        }
    }

    // Return data for editing
    public function edit($id)
    {
        $account = DB::table('accounts')->where('id', $id)->first();
        if (!$account) {
            return response()->json(['success' => false, 'message' => 'Account not found']);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $account->id,
                'bank_name' => $account->bank_name,
                'beneficiary_name' => $account->beneficiary_name,
                'account_number' => Crypt::decryptString($account->account_number_encrypted),
                'ifsc' => Crypt::decryptString($account->ifsc_encrypted),
                'branch' => $account->branch,
                'contact' => $account->contact,
                'status' => $account->status
            ]
        ]);
    }

    // destroy
    public function destroy($id)
    {
        $deleted = DB::table('accounts')->where('id', $id)->delete();
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Account not found']);
        }

        return response()->json(['success' => true, 'message' => 'Account deleted successfully']);
    }
}
