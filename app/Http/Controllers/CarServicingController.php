<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarServicingController extends Controller
{
    //

    public function car_servicing()
    {

        $cars = DB::table('cars')->get();
        return view('backend.pages.car_servicing.index', compact('cars'));
    }


     public function update_car_servicing($id)
    {
        // dd($id);
        $car_data = DB::table('cars')->where('unique_id', $id)->first();
        $document_master = DB::table('document_master')->get();
        $service_master = DB::table('service_master')->get();
        $garage_master = DB::table('garage_master')->get();
        $doc_data = DB::table('car_documents')->where('car_id', $id)->get();
        $car_service_data = DB::table('car_service')->where('car_id', $id)->get();
        return view('backend/pages/car_servicing/servicing', compact('id', 'car_data', 'document_master', 'doc_data', 'service_master', 'garage_master', 'car_service_data'));
    }









}
  