<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\NewAlertAdded;
use Illuminate\Support\Facades\DB;
class AlertController extends Controller
{
    //
     public function index()
    {
        return view('alert');
    }

   
public function store(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:255'
    ]);

    // Insert using raw query
    DB::table('alert_test')->insert([
        'content' => $request->content,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // Get the last inserted row
    $alert = DB::table('alert_test')->latest('id')->first(); // cleaner than DB::select

    if ($alert) {
        // Fire event to broadcast to Pusher
        event(new NewAlertAdded($alert));
    }

    return response()->json([
        'success' => true,
        'message' => 'New alert added successfully!',
        'data' => $alert
    ]);
}
}
