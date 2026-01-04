<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
	
	
	
	
	
	
	
	
public function dashboard()
{
    // Sare counts fetch karo
    $totalCars = DB::table('cars')->count();
    $totalUsers = DB::table('users')->count();
    $totalGarages = DB::table('garage_master')->count();
    $totalLeads = DB::table('leads')->count();
    $totalBookings = DB::table('bookings')->count();
    
    $newLeads = DB::table('leads')->where('status', 1)->count();
    $talkedLeads = DB::table('leads')->where('status', 2)->count();
    
    $jobsPending = DB::table('bookings')->where('status', 'Awaiting Payment')->count();
    $jobsCompleted = DB::table('bookings')->where('status', 'Completed')->count();
    $jobsAssigned = DB::table('bookings')->where('status', 'Confirmed')->count();

    $totalRevenue = DB::table('car_service')->sum('bill_paid');
    $totalDue = DB::table('car_service')->sum('due');

    $recentBookings = DB::table('bookings')
        ->join('cars', 'bookings.car_id', '=', 'cars.id')
        ->select('bookings.*', 'cars.brand', 'cars.model')
        ->limit(5)
        ->orderBy('bookings.id', 'desc')
        ->get();

    // ✅ SABSE ZAROORI: compact() ke andar variables ka naam string me likho
    return view('backend.dashboard', compact(
        'totalCars', 'totalUsers', 'totalGarages', 'totalLeads', 
        'totalBookings', 'newLeads', 'talkedLeads', 'jobsPending', 
        'jobsCompleted', 'jobsAssigned', 'totalRevenue', 'totalDue',
        'recentBookings'
    ));
}















}