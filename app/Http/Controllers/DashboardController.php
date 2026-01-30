<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //


    public function inventory_dashboard()
{
    $stats = [
        'total_products'    => 120,
        'total_investment'  => 450000,
        'new_items'         => 35,
        'expired_warranty'  => 12,
    ];

    $chart = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        'data'   => [10, 25, 18, 30, 22, 40],
    ];

    return view('backend.dashboards.inventory', compact('stats', 'chart'));
}

}
