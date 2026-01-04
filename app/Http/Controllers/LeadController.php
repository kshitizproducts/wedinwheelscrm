<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LeadController extends Controller
{
    // 

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
public function publicShowcase($token) {
    $response = DB::table('client_responses')->where('token', $token)->first();
    if(!$response) abort(404);

    $lead = DB::table('leads')->where('id', $response->lead_id)->first();
    $cars = DB::table('cars')->get(); // Saari options dikhane ke liye

    return view('frontend.public_showcase', compact('lead', 'cars', 'token'));
}





}
