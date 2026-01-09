<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\NewAlertAdded;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class AlertController extends Controller implements HasMiddleware
{
    //

     public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'),

            new Middleware(middleware: 'permission:view alert', only: ['index']),

            new Middleware(middleware: 'permission:create alert', only: ['create', 'store']),

            new Middleware(middleware: 'permission:edit alert', only: ['edit', 'update']),

            new Middleware(middleware: 'permission:delete alert', only: ['delete']),
        ];    
    }




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
