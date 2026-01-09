<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class DriverTaskController extends Controller
{
    public function index()
    {
        $drivers = DB::table('users')
    ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
    ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
    ->select('users.*', 'roles.name as role_name')
    ->where('users.id', 12)
    ->first();

    

        return view('backend.pages.task.driver_task',compact('drivers'));
    }

    public function list(Request $request)
    {
        $page    = $request->get('page', 1);
        $perPage = 10;
        $offset  = ($page - 1) * $perPage;

        $total = DB::table('driver_tasks')->count();

        $data = DB::table('driver_tasks')
            ->orderByRaw('task_time IS NULL, task_time DESC')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'data'         => $data,
                'total'        => $total,
                'per_page'     => $perPage,
                'current_page' => $page,
                'last_page'    => ceil($total / $perPage),
                'from'         => $offset + 1
            ]
        ]);
    }

    public function store(Request $request)
    {
        $taskTime = $request->task_time
            ? str_replace('T', ' ', $request->task_time) . ':00'
            : null;

        DB::table('driver_tasks')->insert([
            'driver_id'       => $request->driver_id ?? 1,
            'client_name'     => $request->client_name,
            'client_mobile'   => $request->client_mobile,
            'pickup_location' => $request->pickup_location,
            'drop_location'   => $request->drop_location,
            'car_number'      => $request->car_number,
            'task_time'       => $taskTime,
            'status'          => $request->status ?? 'pending',
            'remarks'         => $request->remarks,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully'
        ]);
    }

    public function edit($id)
    {
        $task = DB::table('driver_tasks')->where('id', $id)->first();

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    public function update(Request $request)
    {
        $taskTime = $request->task_time
            ? str_replace('T', ' ', $request->task_time) . ':00'
            : null;

        DB::table('driver_tasks')
            ->where('id', $request->id)
            ->update([
                'client_name'     => $request->client_name,
                'client_mobile'   => $request->client_mobile,
                'pickup_location' => $request->pickup_location,
                'drop_location'   => $request->drop_location,
                'car_number'      => $request->car_number,
                'task_time'       => $taskTime,
                'status'          => $request->status,
                'remarks'         => $request->remarks,
                'updated_at'      => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully'
        ]);
    }

    public function delete($id)
    {
        DB::table('driver_tasks')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted'
        ]);
    }




 public function assign_task_to_drivers(Request $request)
    {

        $car_id= $request->car_id;
        $client_id= $request->client_id;
        $driver_id= $request->driver_id;
        $date_time= $request->date_time;
        $source_location= $request->source_location;
        $destination_location= $request->destination_location;


        $insert= DB::table('driver_schedules')->insert([
            'driver_id' => $driver_id,
            'car_id' => $car_id,
            'client_id' => $client_id,
            'booked_date' => $date_time,
            'source_location' => $source_location,
            'destination_location' => $destination_location,
            'status' => 1,
          
        ]);

        if($insert)
        {
             return response()->json([
            'success' => true,
            'message' => 'Task assigned to Driver!!'
        ]);
        }
        else
        {
            
             return response()->json([
            'success' => false,
            'message' => 'Driver assigned Failed!'
        ]);
        }

    }

    

    // public function driver_tasks()
    // {
    //     $driver_schedules= DB::table('driver_schedules')->get();

    //     return view('backend.pages.task.driver_task',compact('driver_schedules'));
    // }
// 

// driver task function start here to show tasks to drivers
// public function driver_tasks()
// {

//     // auth()->check()
// // dd(vars: Auth()->user());    

//     $driver_id =  Auth()->user()->id;   // ✅ logged in driver



//     $driver_schedules = DB::table('driver_schedules')
//         ->where('driver_id', $driver_id)
//         ->orderBy('id', 'desc')
//         ->get();

//     return view('backend.pages.task.driver_task', compact('driver_schedules'));
// }
public function driver_tasks()
{
    if(!auth()->check()){
        return redirect('login')->with('error', 'Please login first!');
    }

    $user = auth()->user();

    if($user->hasRole('superadmin') || $user->id == 1){
        $driver_schedules = DB::table('driver_schedules')
            ->orderBy('id', 'desc')
            ->get();
    } else {
        $driver_schedules = DB::table('driver_schedules')
            ->where('driver_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();
    }

    return view('backend.pages.task.driver_task', compact('driver_schedules'));
}


// ✅ START TRIP

public function driver_task_start(Request $request, $id)
{

    $user_id= Auth()->user()->id;
    $task = DB::table('driver_schedules')
        ->where('id', $id)
        // ->where('driver_id', $user_id)
        ->first();

    if(!$task){
        return response()->json(['success'=>false,'message'=>'Task not found']);
    }

    if($task->status != 1){
        return response()->json(['success'=>false,'message'=>'Trip already started']);
    }

    DB::table('driver_schedules')->where('id',$id)->update([
        'status' => 2,
        'trip_start_time' => now(),
        'updated_at' => now()
    ]);

    return response()->json(['success'=>true,'message'=>'Trip started']);
}

// ✅ END TRIP
public function driver_task_end(Request $request, $id)
{
    $task = DB::table('driver_schedules')
        ->where('id', $id)
        ->where('driver_id', Auth::id())
        ->first();

    if(!$task || $task->status != 2){
        return response()->json(['success'=>false,'message'=>'Trip not in progress']);
    }

    $start = Carbon::parse($task->trip_start_time);
    $end   = now();

    DB::table('driver_schedules')->where('id',$id)->update([
        'status' => 3,
        'trip_end_time' => $end,
        'trip_duration_minutes' => $start->diffInMinutes($end),
        'updated_at' => now()
    ]);

    return response()->json(['success'=>true,'message'=>'Trip completed']);
}


// ✅ ADD / UPDATE REMARK
public function driver_task_remark(Request $request, $id)
{
    $request->validate(['remark'=>'required|string']);
$user_id=$user_id= Auth()->user()->id;
    DB::table('driver_schedule_remarks')->insert([
        'driver_schedule_id' => $id,
        'user_id' => $user_id,
        'user_role' => Auth::user()->getRoleNames()->first() ?? 'driver',
        'remark' => $request->remark,
        'created_at' => now()
    ]);

    return response()->json(['success'=>true,'message'=>'Remark added']);
}


public function driver_task_remarks($id)
{
    $remarks = DB::table('driver_schedule_remarks')
        ->join('users','users.id','=','driver_schedule_remarks.user_id')
        ->where('driver_schedule_id',$id)
        ->orderBy('driver_schedule_remarks.id','asc')
        ->select(
            'driver_schedule_remarks.*',
            'users.name'
        )->get();

    return response()->json($remarks);
}

// ✅ DELETE TASK (only if assigned / not started)
public function delete_driver_task($id)
{
    $driver_id = Auth::id();

    $task = DB::table('driver_schedules')
        ->where('id', $id)
        ->where('driver_id', $driver_id)
        ->first();

    if(!$task){
        return response()->json(['success'=>false,'message'=>'Task not found!']);
    }

    if($task->status == 2 || $task->status == 3){
        return response()->json(['success'=>false,'message'=>'Task cannot be deleted after trip start!']);
    }

    $delete = DB::table('driver_schedules')
        ->where('id', $id)
        ->delete();

    return response()->json([
        'success' => $delete ? true : false,
        'message' => $delete ? 'Task deleted successfully!' : 'Delete failed!'
    ]);
}
// end of showing driver task to their dashboard
public function update_driver_schedule(Request $request, $id)
{
    $car_id = $request->car_id;
    $client_id = $request->client_id;
    $driver_id = $request->driver_id;
    $booked_date = $request->booked_date;   // edit modal hidden datetime-local
    $source_location = $request->source_location;
    $destination_location = $request->destination_location;

    // ✅ Optional validation
    // $request->validate([
    //     'car_id' => 'required',
    //     'client_id' => 'required',
    //     'driver_id' => 'required',
    //     'booked_date' => 'required',
    //     'source_location' => 'required',
    //     'destination_location' => 'required',
    // ]);

    $update = DB::table('driver_schedules')
        ->where('id', $id)
        ->update([
            'driver_id' => $driver_id,
            'car_id' => $car_id,
            'client_id' => $client_id,
            'booked_date' => $booked_date,
            'source_location' => $source_location,
            'destination_location' => $destination_location,
        ]);

    if ($update) {
        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully!'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Update failed or no changes made!'
        ]);
    }
}


public function delete_driver_schedule($id)
{
    $delete = DB::table('driver_schedules')->where('id', $id)->delete();

    if ($delete) {
        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully!'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Delete failed!'
        ]);
    }
}



}
