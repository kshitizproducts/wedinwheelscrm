<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeDocumentationController extends Controller
{
    //

 
    public function employee_docs()
    {
        // $user_docs= DB::table('user_documents')->get();
          $user_docs = DB::table('user_documents')
        ->join('users', 'users.id', '=', 'user_documents.user_id')
        ->select(
            'user_documents.*',
            'users.name',
            'users.email'
        )
        ->orderBy('user_documents.id', 'desc')
        ->get();
        return view('backend.pages.master.employee_docs',compact('user_docs'));
    } 
    

      public function getEmployees(Request $request)
    {
        // NOTE:
        // assume documents are stored in table: employee_documents
        // user table: users

        $perPage = 8;

        $users = DB::table('users')
            ->leftJoin('employee_documents', 'users.id', '=', 'employee_documents.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw("IFNULL(users.status, 1) as status"),
                'employee_documents.aadhar',
                'employee_documents.pan',
                'employee_documents.resume',
                'employee_documents.education',
                'employee_documents.offer_letter',
                'employee_documents.appointment_letter',
                'employee_documents.agreement_letter',
                'employee_documents.bank_details'
            )
            ->orderBy('users.id', 'desc')
            ->paginate($perPage);

        // Make flags for frontend
        $users->getCollection()->transform(function ($u) {

            $u->has_aadhar  = !empty($u->aadhar);
            $u->has_pan     = !empty($u->pan);
            $u->has_resume  = !empty($u->resume);
            $u->has_edu     = !empty($u->education);

            $u->has_offer   = !empty($u->offer_letter);
            $u->has_appoint = !empty($u->appointment_letter);
            $u->has_agree   = !empty($u->agreement_letter);

            $u->has_bank    = !empty($u->bank_details);

            return $u;
        });

        return response()->json([
            'success' => true,
            'users'   => $users
        ]);
    }

    /**
     * Upload/Update Employee documents
     */
    public function updateDocs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'            => 'required|integer|exists:users,id',

            'aadhar'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'pan'                => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'resume'             => 'nullable|file|mimes:pdf,doc,docx|max:4096',
            'education'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'offer_letter'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'appointment_letter' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'agreement_letter'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'bank_details'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $userId = $request->user_id;

        // existing record?
        $existing = DB::table('employee_documents')->where('user_id', $userId)->first();

        $data = [
            'user_id'     => $userId,
            'updated_at'  => now(),
        ];

        // upload helper
        $upload = function ($file, $folder = 'employee_docs') {
            $ext = $file->getClientOriginalExtension();
            $name = Str::uuid() . '.' . $ext;
            return $file->storeAs($folder, $name, 'public'); // storage/app/public/...
        };

        // save files
        if ($request->hasFile('aadhar'))             $data['aadhar'] = $upload($request->file('aadhar'));
        if ($request->hasFile('pan'))                $data['pan'] = $upload($request->file('pan'));
        if ($request->hasFile('resume'))             $data['resume'] = $upload($request->file('resume'));
        if ($request->hasFile('education'))          $data['education'] = $upload($request->file('education'));

        if ($request->hasFile('offer_letter'))       $data['offer_letter'] = $upload($request->file('offer_letter'));
        if ($request->hasFile('appointment_letter')) $data['appointment_letter'] = $upload($request->file('appointment_letter'));
        if ($request->hasFile('agreement_letter'))   $data['agreement_letter'] = $upload($request->file('agreement_letter'));

        if ($request->hasFile('bank_details'))       $data['bank_details'] = $upload($request->file('bank_details'));

        if ($existing) {
            DB::table('employee_documents')->where('user_id', $userId)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('employee_documents')->insert($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Documents updated successfully!'
        ]);
    }
    


 


}
