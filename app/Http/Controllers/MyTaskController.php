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
        $drivers = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'driver')
            ->select('users.*')
            ->get();

        $driver_schedules= DB::table('driver_schedules')->get();
        $cars= DB::table('cars')->get();
        
        return view('backend.pages.master.driver_schedule',compact('drivers',
        'driver_schedules',
        'cars'

    
    ));
    }
}
 