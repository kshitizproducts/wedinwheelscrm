<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class BookingController extends Controller
{
    //
    public function bookings()
    {
        // Logic to retrieve and display bookings
		$cars= DB::table('cars')->get();
        return view('backend.pages.bookings.index',compact('cars')); // Assuming there's a bookings/index.blade.php view
    }

public function calender_and_schedule()
{
    // Database se bookings fetch karna with Car details
    $rawBookings = DB::table('bookings')
        ->join('cars', 'bookings.car_id', '=', 'cars.id')
        ->select('bookings.*', 'cars.brand', 'cars.model')
        ->get();

    // Data ko JS ke liye group karna (Date as Key)
    $formattedBookings = [];
    foreach ($rawBookings as $booking) {
        $date = $booking->booking_date; // column name: booking_date
        $formattedBookings[$date][] = [
            'id'             => $booking->id,
            'car'            => $booking->brand . ' ' . $booking->model,
            'client'         => $booking->client_name,
            'status'         => $booking->payment_status, // Ya jo bhi status field ho
            'time'           => $booking->booking_time,
            'venue'          => $booking->venue,
            'booking_master_url' => url('/bookings') // Aapka main booking route
        ];
    }

    return view('backend.pages.bookings.calender_and_schedule', [
        'bookingsData' => $formattedBookings
    ]);
}
    
	
	
	
	public function index()
    {
        $cars = DB::table('cars')->select('id', 'brand', 'model')->get();
        
        // Fix: 'bookings.car_id' use kiya hai (single dot)
        $bookings = DB::table('bookings')
            ->join('cars', 'bookings.car_id', '=', 'cars.id')
            ->select('bookings.*', 'cars.brand', 'cars.model as car_model')
            ->orderBy('bookings.id', 'desc')
            ->get();

        return view('backend.pages.bookings.index', compact('cars', 'bookings'));
    }

    public function store(Request $request)
    {
        // Data insert logic
        $insert = DB::table('bookings')->insert([
            'client_name'    => $request->client,
            'car_id'         => $request->car,
            'booking_date'   => $request->date,
            'booking_time'   => $request->time,
            'venue'          => $request->venue,
            'event_type'     => $request->event_type,
            'payment_status' => $request->payment_status,
            'notes'          => $request->notes,
            'status'         => 'Confirmed', // Default status
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        if ($insert) {
            return response()->json(['success' => true, 'message' => 'Booking added!']);
        }
        return response()->json(['success' => false, 'message' => 'Error adding booking']);
    }

    public function delete_booking(Request $request)
    {
        $delete = DB::table('bookings')->where('id', $request->id)->delete();
        if ($delete) {
            return response()->json(['success' => true, 'message' => 'Deleted!']);
        }
        return response()->json(['success' => false]);
    }
	
	public function update_booking(Request $request)
{
    try {
        // Step 1: Manual validation taaki error pakda jaye
        if (!$request->booking_id) {
            return response()->json(['success' => false, 'message' => 'Booking ID is missing']);
        }

        // Step 2: Update Query
        $update = DB::table('bookings')
            ->where('id', $request->booking_id)
            ->update([
                'client_name'    => $request->client,
                'car_id'         => $request->car,
                'booking_date'   => $request->date,
                'booking_time'   => $request->time,
                'venue'          => $request->venue,
                'payment_status' => $request->payment_status,
                'updated_at'     => now(),
            ]);

        // Note: Agar user ne koi data change nahi kiya aur direct update dabaya, 
        // toh $update 0 return karega, isliye hum hamesha true bhejenge agar query bina crash hue chali.
        return response()->json(['success' => true, 'message' => 'Booking Updated Successfully!']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}
	
	
	
}
