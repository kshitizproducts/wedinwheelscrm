<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //


public function updatePhoto(Request $request)
{
    $request->validate([
        'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $userId = auth()->id();

    $file = $request->file('profile_photo');
    $fileName = time().'_profile_'.uniqid().'.'.$file->getClientOriginalExtension();
    $file->move(public_path('uploads/profile_photos'), $fileName);

    $path = 'uploads/profile_photos/'.$fileName;

    DB::table('users')
      ->where('id', $userId)
      ->update([
          'profile_photo' => $path,
          'updated_at' => now()
      ]);

    return back()->with('success', 'Profile photo updated successfully');
}


    public function profile()
    {
        $user = auth()->user(); // Get the authenticated user
        $userData = DB::table('users')->where('id', $user->id)->first(); // Fetch all data from the users table for the authenticated user
        $userDocs = DB::table('user_documents')->where('user_id', auth()->id())->first();
        return view('backend.profile.profile', compact('userData','userDocs'));
    }

public function update(Request $request)
{
    $request->validate([
        'doc_key' => 'required',
        'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    $docKey = $request->doc_key;
    $allowed = ['aadhar','pan','resume','offer_letter','appointment_letter','agreement_letter','bank_passbook','education_certificates'];

    if(!in_array($docKey, $allowed)){
        return back()->with('error', 'Invalid document type');
    }

    $userId = auth()->id();

    // upload file
    $file = $request->file('document_file');
    $fileName = time().'_'.$docKey.'_'.uniqid().'.'.$file->getClientOriginalExtension();
    $path = $file->move(public_path('uploads/user_documents'), $fileName);
    $dbPath = 'uploads/user_documents/'.$fileName;

    // check existing row
    $exists = DB::table('user_documents')->where('user_id', $userId)->first();

    if($exists){
        DB::table('user_documents')
            ->where('user_id', $userId)
            ->update([
                $docKey => $dbPath,
                'updated_at' => now()
            ]);
    }else{
        DB::table('user_documents')
            ->insert([
                'user_id' => $userId,
                $docKey => $dbPath,
                'created_at' => now(),
                'updated_at' => now()
            ]);
    }

    return back()->with('success', 'Document updated successfully');
}



public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = DB::table('users')->where('id', auth()->id())->first();

    if(!$user){
        return back()->with('error', 'User not found.');
    }

    // ✅ check current password
    if(!Hash::check($request->current_password, $user->password)){
        return back()->with('error', 'Current password is incorrect.');
    }

    // ✅ update password
    DB::table('users')->where('id', auth()->id())->update([
        'password' => Hash::make($request->new_password),
        'updated_at' => now()
    ]);

    return back()->with('success', 'Password updated successfully.');
}





}