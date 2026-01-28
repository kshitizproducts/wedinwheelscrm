<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanyprofileController extends Controller
{
    public function index()
    {
        // Ab saara data ek hi table 'legal_documents' se aayega
        $doc = DB::table('legal_documents')->where('id', 1)->first();

        if (!$doc) {
            $doc = (object)[]; 
        }

        return view('backend.pages.company_profile.index', compact('doc'));
    }

    // Aapka upload logic
    private function uploadDoc($file, $folder = 'legal')
    {
        $uploadPath = public_path('uploads/' . $folder);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $filename);
        return 'uploads/' . $folder . '/' . $filename;
    }

    // 1. Basic Profile Update (Logo & Company Info)
    public function updateBasicInfo(Request $request)
    {
        $data = $request->only(['company_name', 'display_name', 'tagline', 'business_type', 'industry', 'year_established', 'status']);
        
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $this->uploadDoc($request->file('profile_picture'), 'profile');
        }

        DB::table('legal_documents')->where('id', 1)->update($data);
        return back()->with('success_basic', 'Basic profile updated successfully!');
    }

    // 2. Legal Documents Update
    public function updateLegalCompliance(Request $request)
    {
        $fields = ['gst', 'pan', 'trade', 'msme', 'rent', 'electricity'];
        $updateData = [];

        foreach ($fields as $f) {
            $numKey = ($f == 'rent' || $f == 'electricity') ? null : $f . '_number';
            if ($numKey) $updateData[$numKey] = $request->$numKey;

            $updateData[$f . '_expiry'] = $request->{$f . '_expiry'};
            $updateData[$f . '_notify_days'] = $request->{$f . '_notify_days'};

            if ($request->hasFile($f . '_file')) {
                $updateData[$f . '_file'] = $this->uploadDoc($request->file($f . '_file'));
            }
        }

        DB::table('legal_documents')->where('id', 1)->update($updateData);
        return back()->with('success_legal', 'Legal documents updated!');
    }

    // 3. Banking Update
    public function updateBanking(Request $request)
    {
        // dd($request->all());
        DB::table('legal_documents')->where('id', 1)->update([
            'acc_holder' => $request->acc_holder,
            'acc_number' => $request->acc_number,
            'ifsc'       => $request->ifsc,
            'upi_id'     => $request->upi_id,
        ]);

        return back()->with('success_bank', 'Banking information updated!');
    }
}