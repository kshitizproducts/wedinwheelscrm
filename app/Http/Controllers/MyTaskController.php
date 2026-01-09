<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyTaskController extends Controller
{
    //

    public function my_task()
    {
        return view('backend.pages.task.my_task');
    } 



    public function driver_schedule()
{
    // drivers with role id 12
    $drivers = DB::table('users')
        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->select('users.*', 'roles.name as role_name')
        ->where('roles.id', 12)
        ->get();

    $clients = DB::table('leads')->get();

    $driver_schedules = DB::table('driver_schedules')->get();

    $cars = DB::table('cars')->get();

    // already assigned drivers
    $assignedDrivers = DB::table('driver_schedules')
        ->where('status', 1)
        ->pluck('driver_id')
        ->toArray();


   $assignedDrivers = DB::table('driver_schedules as ds')
    ->leftJoin('cars as c', 'c.id', '=', 'ds.car_id')
    ->leftJoin('leads as l', 'l.id', '=', 'ds.client_id')
    ->leftJoin('users as u', 'u.id', '=', 'ds.driver_id')
    ->where('ds.status', 1)
    ->pluck('ds.driver_id')   // 🔥 FIX
    ->toArray();              // 🔥 FIX


$assignedDrivers = DB::table('driver_schedules')->pluck('driver_id')->toArray();
$assignedClients = DB::table('driver_schedules')->pluck('client_id')->toArray();

return view('backend.pages.master.driver_schedule', compact(
    'cars','clients','drivers','driver_schedules',
    'assignedDrivers','assignedClients'
));


  
}

}
 