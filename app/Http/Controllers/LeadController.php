<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LeadController extends Controller
{
    // 
    public function save_client_car_filter(Request $request) 
{

    dd($request->all());
    // Validation
    $request->validate([
        'client_name' => 'required',
        'contact' => 'required',
        'selected_cars' => 'required|array' // Check karega ki kam se kam 1 car select ho
    ]);

    // Data prepare karein
    $data = [
        'client_name'   => $request->client_name,
        'contact'       => $request->contact,
        // Array ko string (JSON) mein badal kar save karenge
        'selected_cars' => json_encode($request->selected_cars), 
        'updated_at'    => now(),
        'created_at'    => now(),
    ];

    // Database mein insert karein (Table name check kar lena apni leads wali)
    DB::table('leads')->insert($data);

    return response()->json(['success' => true, 'message' => 'Client data and selected cars saved!']);
}



 
// public function share_available_cars(Request $request)
// {
//     $lead_id = $request->lead_id;
//     $car_ids = $request->selected_cars; // array of car ids

//     $token = Str::random(50);

//     DB::insert("
//         INSERT INTO car_shares (lead_id, token, car_ids, created_at, updated_at)
//         VALUES (?, ?, ?, NOW(), NOW())
//     ", [
//         $lead_id,
//         $token,
//         json_encode($car_ids)
//     ]);

//     $url = url('/car-share/'.$token);

//     return response()->json([
//         'success' => true,
//         'url' => $url
//     ]);
// }
public function share_available_cars(Request $request)
{
    $lead_id = $request->lead_id;
    $car_ids = $request->selected_cars;

    $token = Str::random(50);


    DB::insert("
        INSERT INTO car_shares (lead_id, token, car_ids, created_at, updated_at)
        VALUES (?, ?, ?, NOW(), NOW())
    ", [$lead_id, $token, json_encode($car_ids)]);

    $url = url('/car-share/'.$token);

    return response()->json([
        'success' => true,
        'url' => $url,
        'token' => $token
    ]);
}

public function publicCars($token)
{
    $share = DB::selectOne("SELECT * FROM car_shares WHERE token = ?", [$token]);
    if (!$share) {
        abort(404, "Invalid Link");
    }
    $carIds = json_decode($share->car_ids, true);
    if (!is_array($carIds) || count($carIds) == 0) {
        $carIds = [];
    }
    $cars = [];
    if (count($carIds) > 0) {
        $placeholders = implode(',', array_fill(0, count($carIds), '?'));
        $cars = DB::select("SELECT * FROM cars WHERE id IN ($placeholders)", $carIds);
    }
    $lead = DB::selectOne("SELECT * FROM leads WHERE id = ?", [$share->lead_id]);
    return view('public.share_cars', [
        'cars' => $cars,
        'lead' => $lead,
        'token' => $token,
        'share' => $share
    ]);
}
// public function confirmCar(Request $request, $token)
// {
//     $request->validate([
//         'selected_car' => 'required'
//     ]);

//     $share = DB::selectOne("SELECT * FROM car_shares WHERE token = ?", [$token]);
//     if(!$share){
//         abort(404);
//     }

//     // update share
//     DB::update("
//         UPDATE car_shares 
//         SET client_selected_car_id = ?, status = 1, updated_at = NOW()
//         WHERE token = ?
//     ", [$request->selected_car, $token]);

//     // update lead
//     DB::update("
//         UPDATE leads 
//         SET car_id = ?, status = 2, updated_at = NOW()
//         WHERE id = ?
//     ", [$request->selected_car, $share->lead_id]);

//     return back()->with('success','Car Selected Successfully!');
// }
  public function confirmCar(Request $request, $token)
    {
        $request->validate([
            'selected_car' => 'required'
        ]);

        $share = DB::table('car_shares')->where('token', $token)->first();
        if (!$share) abort(404);

        // ✅ already confirmed
        if ($share->status == 1) {
            return back()->with('already', 'Your response already recorded.');
        }

        // ✅ update car_shares
        DB::table('car_shares')->where('token', $token)->update([
            'client_selected_car_id' => $request->selected_car,
            'status' => 1,
            'updated_at' => now(),
        ]);

        // ✅ update lead
        DB::table('leads')->where('id', $share->lead_id)->update([
            'car_id' => $request->selected_car,
            'status' => 2,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Thank you! Your response has been submitted successfully.');
    }

public function finalizeCar(Request $request)
{
    $request->validate([
        'lead_id' => 'required',
        'car_id'  => 'required'
    ]);

    // lead update final
    DB::update("
        UPDATE leads 
        SET car_id = ?, status = 3, updated_at = NOW()
        WHERE id = ?
    ", [$request->car_id, $request->lead_id]);

    return back()->with('success', '✅ Car Finalized Successfully!');
}


public function delete_car_share_token(Request $request)
{
    $token = $request->token;

    DB::delete("DELETE FROM car_shares WHERE token = ?", [$token]);

    return response()->json([
        'success' => true
    ]);
}








    public function lead_generation()
    {
        $leads = DB::table('leads')
            ->whereNotNull('manager_id')
            ->get();

        $manager_list = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereIn('roles.id', [13, 'Driver'])
            ->select('users.*', 'roles.name as role_name')
            ->get();


        $un_assigned_leads = DB::table('leads')
            ->whereNull('manager_id')->get();
        return view('backend.pages.master.lead_generation', compact('leads', 'manager_list', 'un_assigned_leads'));
    } 


    public function assign_lead_to_manager(Request $request)
    {
        $lead_id = $request->input('lead_id');
        $manager_id = $request->input('manager_id');

        // Update the lead with the assigned manager
        $updated = DB::table('leads')
            ->where('id', $lead_id)
            ->update(['manager_id' => $manager_id]);

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Manager Assigned successfully!!!']);
        } else {
            return response()->json(['success' => true, 'message' => 'Manager assign failed!!!']);
        }
    }



    public function save_client_data(Request $request)
    {
        $client_name = $request->client_name;
        $contact = $request->contact;
		$whatsapp_no = $request->whatsapp_no;
		
        $enquiry_type = $request->enquiry_type;
        $branch = $request->branch;
        $source = $request->source;
        $event_type = $request->event_type;
          $booking_date = $request->booking_date;
        $status = $request->status;

        // Mappings for short codes

        $source_map = [
            'Google' => 'G',
            'WhatsApp' => 'W',
            'Facebook' => 'F',
            'Instagram' => 'I',
            'Walking' => 'K',
            'Reference' => 'R',

        ];



        $event_map = [
            'Barat Entry' => 'wed',
            'Prewedding' => 'pre',
            'Small Event' => 'eng',
            'Corporate' => 'anni',

        ];


        $enquiry_map = [
            'Potential' => 'PT',
            'Regular' => 'RG',
            'Hot Leads' => 'HL',
            'Instant' => 'IS',
            'VIP' => 'VIP',
            'Friends' => 'FR',
            'Event Organizers' => 'EO',
        ];

        $branch_map = [
            'WW' => 'WW',
            'FW' => 'FW',
            'SW' => 'SW',
        ];





        // Fetch short codes safely (default to empty if not found)
        $short_enquiry = $enquiry_map[$enquiry_type] ?? '';
        $short_branch = $branch_map[$branch] ?? '';
        $short_source = $source_map[$source] ?? '';
        $short_event = $event_map[$event_type] ?? '';


        $count = DB::table('leads')->count();
        // Generate Unique ID
        $unique_id = $short_enquiry . '/' . date("dmy") . '/' . $short_branch . '/' . $short_source . '/' . $short_event . '/' . $count++;
        // dd($unique_id);
        // Insert into DB
        $insert = DB::table('leads')->insert([
            'unique_id' => $unique_id,
            'client_name' => $client_name,
            'contact' => $contact,
			'whatsapp_no'=>$whatsapp_no,
            'enquiry_type' => $enquiry_type,
            'branch' => $branch,
            'source' => $source,
            'event_type' => $event_type,
            'booking_date'=>$booking_date,
            'status' => $status,
        ]);

        if ($insert) {
            return response()->json(['success' => true, 'message' => 'Client added successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Database insertion failed!']);
        }
    }



    public function my_leads()
    {
        $leads = DB::table('leads')->get();
        $manager_list = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereIn('roles.id', [13, 'Driver'])
            ->select('users.*', 'roles.name as role_name')
            ->get();

 
        $un_assigned_leads = DB::table('leads')
            ->whereNull('manager_id')->get(); 


        $cars = DB::table('cars')->get();
        return view('backend.pages.master.my_leads', compact('leads', 'manager_list', 'un_assigned_leads', 'cars'));

    }
 

 
 
 
 
 
 
 
 
 
 
 
 public function save_client_data_from_client(Request $request) {
    // Lead update logic
    DB::table('leads')->where('id', $request->lead_id)->update([
        'client_name' => $request->client_name,
        'contact' => $request->contact,
        'car_id' => $request->car, // Jo car aapne assign ki initial
        'updated_at' => now()
    ]);

    // Unique Token for Public URL
    $token = Str::random(32);
    DB::table('client_responses')->updateOrInsert(
        ['lead_id' => $request->lead_id],
        ['token' => $token, 'created_at' => now()]
    );

    return response()->json(['success' => true, 'url' => route('public.showcase', $token)]);
}

// Public Page Logic
// public function publicShowcase($token)
// {
//     $response = DB::table('client_responses')->where('token', $token)->first();
//     if(!$response) abort(404);

//     $lead = DB::table('leads')->where('id', $response->lead_id)->first();

//     // ✅ car list
//     $cars = DB::table('cars')->get();

//     // ✅ selected car details if already responded
//     $selectedCar = null;
//     if($response->status == 1 && $response->selected_car_id){
//         $selectedCar = DB::table('cars')->where('id', $response->selected_car_id)->first();
//     }

//     return view('frontend.public_showcase', compact('lead','cars','token','response','selectedCar'));
// }


 public function publicShowcase($token)
    {
        // car_shares table se token verify
        $share = DB::table('car_shares')->where('token', $token)->first();
        if (!$share) abort(404);

        // lead details
        $lead = DB::table('leads')->where('id', $share->lead_id)->first();

        // ✅ car list sirf un cars ki jo share me hai
        $cars = [];
        if (!empty($share->car_ids)) {
            $carIds = array_filter(explode(',', $share->car_ids));
            if (count($carIds) > 0) {
                $cars = DB::table('cars')->whereIn('id', $carIds)->get();
            }
        }

        // ✅ selected car details (already confirmed case)
        $selectedCar = null;
        if ($share->status == 1 && $share->client_selected_car_id) {
            $selectedCar = DB::table('cars')->where('id', $share->client_selected_car_id)->first();
        }

        return view('frontend.public_showcase', compact('lead', 'cars', 'token', 'share', 'selectedCar'));
    }
public function confirmPublicCar(Request $request, $token)
{
    $request->validate([
        'selected_car' => 'required'
    ]);

    $response = DB::table('client_responses')->where('token', $token)->first();
    if(!$response) abort(404);

    // ✅ already submitted => deny
    if($response->status == 1){
        return redirect()->back()->with('already', 'Your response already recorded.');
    }

    // ✅ update response table
    DB::table('client_responses')->where('token', $token)->update([
        'selected_car_id' => $request->selected_car,
        'status' => 1,
        'updated_at' => now(),
    ]);

    // ✅ update lead table optional
    DB::table('leads')->where('id', $response->lead_id)->update([
        'car_id' => $request->selected_car,
        'status' => 2,
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Thank you! Your response has been submitted successfully.');
}





}
