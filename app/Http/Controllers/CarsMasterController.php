<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
class CarsMasterController extends Controller
{
    //



    // In your Laravel Controller
public function car_status()
    {
        // Aapka Access Token
        $accessToken = "02bad839-f0de-4d74-8283-27d937957eaa";
        
        // API Call
        $response = Http::get("https://api.wheelseye.com/currentLoc?accessToken={$accessToken}");

        $vehicleList = [];
        $stats = [
            'total' => 0,
            'moving' => 0,
            'stopped' => 0,
            'idle' => 0
        ];

        if ($response->successful()) {
            $data = $response->json();
            $vehicleList = $data['data']['list'] ?? [];

            // Stats Calculate kar lete hain UI ke liye
            $stats['total'] = count($vehicleList);
            foreach($vehicleList as $v) {
                if($v['ignition'] && $v['speed'] > 0) {
                    $stats['moving']++;
                } elseif($v['ignition'] && $v['speed'] == 0) {
                    $stats['idle']++;
                } else {
                    $stats['stopped']++;
                }
            }
        }

        // View return with data
        return view('backend.pages.car_status.index', compact('vehicleList', 'stats'));
    }


    public function cars_master()
    {
        $cars = DB::table('cars')->get();
        return view('backend.pages.master.cars', compact('cars'));
    }
 
 
    public function add_new_cars(Request $request)
    {
        // dd($request->all());


        $car_name = $request->car_name;
        $brand_name = $request->brand_name;
        $model_name = $request->model_name;
        $rate_per_km = $request->rate_per_km;
        $status = $request->status;



        $unique_id = time();


        // inserting into database for the car master entry

        $insert = DB::table('cars')->insert([
            'unique_id' => $unique_id,
            'name' => $car_name,
            'brand' => $brand_name,
            'model' => $model_name,
            'rate_per_km' => $rate_per_km,
            'status' => $status
        ]);


        if ($insert) {
            return response()->json(['success' => true, 'message' => 'Car Added successfully!!!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Car addition failed!!!']);
        }

    }




 public function update_new_cars(Request $request)
{
    // Validate inputs - unique_id check aur status ranges fix kiye hain
    $validated = $request->validate([
        'unique_id'   => 'required',
        'car_name'    => 'required|string|max:255',
        'brand_name'  => 'required|string|max:255',
        'model_name'  => 'required|string|max:255',
        'rate_per_km' => 'required|numeric|min:0',
        'status'      => 'required' // Isse 1,2,3,4,5 sab allow ho jayenge
    ]);

    // Update the car entry
    $update = DB::table('cars')
        ->where('unique_id', $request->unique_id)
        ->update([
            'name'        => $request->car_name,
            'brand'       => $request->brand_name,
            'model'       => $request->model_name,
            'rate_per_km' => $request->rate_per_km,
            'status'      => $request->status,
            'updated_at'  => now()
        ]);

    // Laravel update tabhi 'true' deta hai jab actually koi data change ho.
    // Isliye hum safety ke liye check karte hain.
    return response()->json([
        'success' => true, 
        'message' => 'Car record processed successfully!'
    ]);
}

    public function delete_new_cars(Request $request)
    {
        // Validate the inputs
        $validated = $request->validate([
            'unique_id' => 'required|numeric',

        ]);

        // Update the car entry in database 
        $delete = DB::table('cars')
            ->where('unique_id', $validated['unique_id'])
            ->delete();

        if ($delete) {
            return response()->json(['success' => true, 'message' => 'Car Deleted Successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Car Deletion Failed!']);
        }

    }


 





    public function car_profile($id)
    {
        $car_data = DB::table('cars')->where('unique_id', $id)->first();
        $car_documents = DB::table('car_documents')->where('car_id', $id)->get();
        $car_service = DB::table('car_service')->where('car_id', $id)->get();
        return view('backend/pages/cars/profile', compact('car_data', 'car_documents', 'car_service'));
    }

    public function update_car_profile($id)
    {
        // dd($id);
        $car_data = DB::table('cars')->where('unique_id', $id)->first();
        $document_master = DB::table('document_master')->get();
        $service_master = DB::table('service_master')->get();
        $garage_master = DB::table('garage_master')->get();
        $doc_data = DB::table('car_documents')->where('car_id', $id)->get();
        $car_service_data = DB::table('car_service')->where('car_id', $id)->get();
        return view('backend/pages/cars/edit_profile', compact('id', 'car_data', 'document_master', 'doc_data', 'service_master', 'garage_master', 'car_service_data'));
    }

  
    public function updateProfilePart1(Request $request)
    {
        try {
            $car_id = $request->input('car_id');
            $brand_name = $request->input('brand_name');
            $car_info = $request->input('car_info');
            $car_model = $request->input('car_model');
            $view_360 = $request->input('360_url');


            $profile_pic = null;
            $images = [];
            $videos = [];

            // Profile photo
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $filename = time() . '_profile.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/cars/profile'), $filename);
                $profile_pic = 'uploads/cars/profile/' . $filename;
            }

            // Multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imgName = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                    $img->move(public_path('uploads/cars/images'), $imgName);
                    $images[] = 'uploads/cars/images/' . $imgName;
                }
            }

            // Multiple videos
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $vid) {
                    $vidName = time() . '_' . uniqid() . '.' . $vid->getClientOriginalExtension();
                    $vid->move(public_path('uploads/cars/videos'), $vidName);
                    $videos[] = 'uploads/cars/videos/' . $vidName;
                }
            }

            // Prepare data for update
            $data = [
                'brand' => $brand_name,
                'car_desc' => $car_info,
                'model' => $car_model,
            ];

            if ($profile_pic) {
                $data['profile_pic'] = $profile_pic;
            }

            if (!empty($images)) {
                $data['images'] = json_encode($images);
            }

            if (!empty($videos)) {
                $data['videos'] = json_encode($videos);
            }

            // Raw DB update
            DB::table('cars')->where('unique_id', $car_id)->update($data);

            // ✅ Return JSON response
            return response()->json([
                'success' => true,
                'message' => 'Car profile updated successfully!',
                'profile_pic' => $profile_pic ?? null,
                'images' => $images,
                'videos' => $videos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }




    public function updateProfilePart2(Request $request)
    {
        $car_id = $request->car_id;
        $mileage = $request->mileage;
        $fuel_type = $request->fuel_type;
        $seats = $request->seats;
        $engine = $request->engine;
        $registration_no = $request->registration_no;
        $location = $request->location;
        $next_availability = $request->next_availability;
        $owner_name = $request->owner_name;
        $status = $request->status;
        $duplicate_keys = $request->duplicate_keys;
        


        try {
            $data = [
                'owner_name' => $owner_name,
                'mileage' => $mileage,
                'fuel_type' => $fuel_type,
                'seat_capacity' => $seats,
                'engine' => $engine,
                'registration_no' => $registration_no,
                'location' => $location,
                'next_availability' => $next_availability,
                'duplicate_keys' => $duplicate_keys,
                'status' => $status
            ];

            $update = DB::table('cars')->where('unique_id', $car_id)->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Car profile updated successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }


  public function save_imp_docs(Request $request)
{
    $car_id = $request->car_id;

    $uploadPath = public_path('uploads/cars/important_documents');

    // Create folder if it doesn't exist
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $dataToUpdate = [];

    // RC Book
    if ($request->hasFile('rc_book')) {
        $rc = $request->file('rc_book');
        $rc_filename = time() . '_rc_' . uniqid() . '.' . $rc->getClientOriginalExtension();
        $rc->move($uploadPath, $rc_filename);
        $dataToUpdate['rc_book'] = 'uploads/cars/important_documents/' . $rc_filename;
    }

    // Pollution
    if ($request->hasFile('pollution')) {
        $pollution = $request->file('pollution');
        $pollution_filename = time() . '_pollution_' . uniqid() . '.' . $pollution->getClientOriginalExtension();
        $pollution->move($uploadPath, $pollution_filename);
        $dataToUpdate['pollution'] = 'uploads/cars/important_documents/' . $pollution_filename;
    }

    // Insurance
    if ($request->hasFile('insurance')) {
        $insurance = $request->file('insurance');
        $insurance_filename = time() . '_insurance_' . uniqid() . '.' . $insurance->getClientOriginalExtension();
        $insurance->move($uploadPath, $insurance_filename);
        $dataToUpdate['insurance'] = 'uploads/cars/important_documents/' . $insurance_filename;
    }

    // Update only if any file was uploaded
    if (!empty($dataToUpdate)) {
        DB::table('cars')
            ->where('unique_id', $car_id)
            ->update($dataToUpdate);
    }

    return response()->json(['success' => true, 'message' => 'Important documents uploaded successfully!!!']);
}




    public function add_document(Request $request)
    {
        // dd($request->all());


        $car_id = $request->car_id;
        $document_name = $request->document_name;
        $issue_date = $request->issue_date;
        $expiry_date = $request->expiry_date;
        $reminder_days = $request->reminder_days;


        $insert = DB::table('car_documents')->insert([
            'car_id' => $car_id,
            'document_id' => $document_name,
            'issued_date' => $issue_date,
            'expiry_date' => $expiry_date,
            'reminder_within' => $reminder_days,
            'status' => 1
        ]);

        if ($insert) {
            return response()->json(['success' => true, 'message' => 'Document Added successfully!!!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Document Addition failed!!!']);
        }

    }




    public function update_document(Request $request)
    {
        // dd($request->all());


        $table_id = $request->table_id;
        $document_name = $request->document_name;
        $issue_date = $request->issue_date;
        $expiry_date = $request->expiry_date;
        $reminder_days = $request->reminder_within;
        $status = $request->status;


        $update = DB::table('car_documents')
            ->where('id', $table_id)
            ->update([
                'document_id' => $document_name,
                'issued_date' => $issue_date,
                'expiry_date' => $expiry_date,
                'reminder_within' => $reminder_days,
                'status' => $status
            ]);

        if ($update) {
            return response()->json(['success' => true, 'message' => 'Document Added successfully!!!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Document Addition failed!!!']);
        }

    }




    public function delete_document_car(Request $request)
    {
        $id = $request->doc_id;
        $delete = DB::table('car_documents')->where('id', $id)->delete();
        if ($delete) {
            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully!',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No document found with the given ID.',
            ]);
        }

    }


public function add_servicing(Request $request)
{
    $userId = auth()->id();
    $car_id = $request->car_id;

    // Folder path setup (Same as your imp_docs)
    $uploadPath = public_path('uploads/cars/servicing_invoices');

    // Create folder if it doesn't exist
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $invoicePaths = [];

    // Multiple Invoice Upload Logic
    if ($request->hasFile('invoice')) {
        foreach ($request->file('invoice') as $file) {
            // Unique filename generate karna
            $filename = time() . '_invoice_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // File ko public folder mein move karna
            $file->move($uploadPath, $filename);
            
            // Database ke liye relative path store karna
            $invoicePaths[] = 'uploads/cars/servicing_invoices/' . $filename;
        }
    }

    // Database mein data insert karna
    $insert = DB::table('car_service')->insert([
        'car_id'          => $car_id,
        'garage_id'       => $request->garage,
        'service_type_id' => $request->service_master,
        'billed_on_date'  => $request->service_date,
        'cost'            => $request->total_amount,
        'bill_paid'       => $request->amount_paid,
        'due'             => $request->due_amount,
        'invoice'         => json_encode($invoicePaths), // Array ko JSON string mein badalna
        'status'          => $request->payment_status,
        'created_by'      => $userId,
        'created_at'      => now(),
    ]);

    if ($insert) {
        return response()->json([
            'success' => true, 
            'message' => 'New servicing record added successfully!!!'
        ]);
    } else {
        return response()->json([
            'success' => false, 
            'message' => 'Failed to save record.'
        ]);
    }
}

  // --- UPDATE FUNCTION ---
public function update_servicing(Request $request)
{
    $userId = auth()->id();
    $table_id = $request->table_id;
    $uploadPath = public_path('uploads/cars/servicing_invoices');

    $record = DB::table('car_service')->where('id', $table_id)->first();
    if (!$record) {
        return response()->json(['success' => false, 'message' => 'Record not found.']);
    }

    $invoicePaths = json_decode($record->invoice ?? '[]', true);

    if ($request->hasFile('invoice')) {
        // Purani files delete karo (Faltu space na bhare)
        if (!empty($invoicePaths)) {
            foreach ($invoicePaths as $oldRelativePath) {
                $fullOldPath = public_path($oldRelativePath);
                if (file_exists($fullOldPath) && !is_dir($fullOldPath)) {
                    unlink($fullOldPath); 
                }
            }
        }

        // Nayi files upload
        $invoicePaths = []; 
        foreach ($request->file('invoice') as $file) {
            $filename = time() . '_invoice_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $invoicePaths[] = 'uploads/cars/servicing_invoices/' . $filename;
        }
    }

    DB::table('car_service')->where('id', $table_id)->update([
        'garage_id'       => $request->garage,
        'service_type_id' => $request->service_master,
        'billed_on_date'  => $request->service_date,
        'cost'            => $request->total_amount,
        'bill_paid'       => $request->amount_paid,
        'due'             => $request->due_amount,
        'invoice'         => json_encode($invoicePaths),
        'status'          => $request->payment_status,
        'updated_by'      => $userId,
        'updated_at'      => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Updated and old files removed!']);
}

// --- DELETE FUNCTION ---
public function delete_btn_servicing(Request $request)
{
    $table_id = $request->table_id;
    $record = DB::table('car_service')->where('id', $table_id)->first();

    if ($record) {
        $invoicePaths = json_decode($record->invoice ?? '[]', true);
        
        // Directory se saari files hatao
        if (!empty($invoicePaths)) {
            foreach ($invoicePaths as $path) {
                $fullPath = public_path($path);
                if (file_exists($fullPath) && !is_dir($fullPath)) {
                    unlink($fullPath);
                }
            }
        }

        // Data delete karo
        DB::table('car_service')->where('id', $table_id)->delete();
        return response()->json(['success' => true, 'message' => 'Record and files deleted permanently!']);
    }

    return response()->json(['success' => false, 'message' => 'Record not found.']);
}




    public function additional_info_car(Request $request)
    {
        $request->validate([
            'car_id' => 'required',
            'car_features' => 'nullable|string',
        ]);

        $features = $request->car_features;

        $update = DB::table('cars')
            ->where('unique_id', $request->car_id) // change to 'id' if needed
            ->update([
                'additional_details' => $features,
                'updated_at' => now(),
            ]);

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Details updated successfully!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Detail updation failed!'
            ]);
        }
    }




}
