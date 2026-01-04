<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\TestNotification;
class NotificationController extends Controller
{
    //
    public function add_notification()
    {
        $data= DB::table('notifications')->orderBy('id', 'desc')->get();
        return view('backend.pages.master.notification',compact('data'));
    }


    public function new_notification(Request $request)
    {
        // Validate request
        $request->validate([
            'visibility' => 'required|array',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            // Convert array to comma-separated string
            $visible_to = implode(',', $request->visibility);

            // Raw DB insert
            DB::insert('INSERT INTO notifications (visible_to, title, description, updated_at, created_at) VALUES (?, ?, ?, NOW(), NOW())', [
                $visible_to,
                $request->title,
                $request->description,
            ]);


            $message= $request->title.$request->description;
            // Trigger real-time event AFTER successful insert
            $sent = event(new TestNotification([
                'author' => $request->title,
                'title' => $message,
            ]));


            // event(new TestNotification([
            //     'author' => 'hii',
            //     'title' => 'hello',
            // ]));



            // Return JSON success response
            return response()->json([
                'success' => true,
                'message' => 'Notification added successfully!',
                'data' => [
                    'visible_to' => $visible_to,
                    'title' => $request->title,
                    'description' => $request->description,
                ]
            ]);

        } catch (\Exception $e) {
            // Return JSON error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to add notification!',
                'error' => $e->getMessage()
            ], 500);
        }
    }




}
