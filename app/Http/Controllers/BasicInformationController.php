<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class BasicInformationController extends Controller implements HasMiddleware
{
     public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'), 

            new Middleware(middleware: 'permission:view companyprofile', only: ['index']),

            new Middleware(middleware: 'permission:create companyprofile', only: ['create', 'store']),

            new Middleware(middleware: 'permission:edit companyprofile', only: ['edit', 'update']),

            new Middleware(middleware: 'permission:delete companyprofile', only: ['delete']),
        ];    
    }
    
    public function index()
    {
        return view('backend.pages/basic_information.index');
    }

    public function get()
    {
        $data = DB::table('business_profiles')->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

   public function basic_information_save(Request $r)
{
    $id = $r->id;

    // Get existing record if editing
    $existing = null;
    if ($id) {
        $existing = DB::table('business_profiles')->where('id', $id)->first();
    }

    $data = [
        'company_name'     => $r->company_name,
        'display_name'     => $r->display_name,
        'tagline'          => $r->tagline,
        'business_type'    => $r->business_type,
        'industry'         => $r->industry,
        'year_established' => $r->year_established,
        'status'           => $r->status ?? 1,
    ];

    // HANDLE IMAGE
    if ($r->hasFile('profile_picture')) {

        $file = $r->file('profile_picture');

        $uploadPath = public_path('uploads/business');

        // create unique filename
        $filename = time() . '_logo_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // move file
        $file->move($uploadPath, $filename);

        // save db path
        $data['profile_picture'] = 'uploads/business/' . $filename;

        // DELETE OLD FILE WHEN EDITING
        if ($existing && $existing->profile_picture && file_exists(public_path($existing->profile_picture))) {
            @unlink(public_path($existing->profile_picture));
        }
    }
 
    // UPDATE
    if ($id) {
        DB::table('business_profiles')->where('id', $id)->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    }

    // INSERT
    DB::table('business_profiles')->insert($data);

    return response()->json([
        'success' => true,
        'message' => 'Saved successfully'
    ]);
}

    public function edit($id)
    {
        $row = DB::table('business_profiles')->find($id);

        return response()->json([
            'success' => true,
            'data' => $row
        ]);
    }

    public function delete($id)
    {
        DB::table('business_profiles')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
