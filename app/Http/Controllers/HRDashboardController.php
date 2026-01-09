<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HRDashboardController extends Controller
{
    public function index()
    {
        return view('backend.pages.hr_dashboard.index');
    }

    public function get()
    {
        // Dashboard Stats
        $stats = [

            'total_present' => DB::selectOne("
                SELECT COUNT(*) total FROM employees WHERE status='present'
            ")->total,

            'total_absent' => DB::selectOne("
                SELECT COUNT(*) total FROM employees WHERE status='absent'
            ")->total,

            'total_leave' => DB::selectOne("
                SELECT COUNT(*) total FROM employees WHERE status='leave'
            ")->total,

            'new_joinee' => DB::selectOne("
                SELECT COUNT(*) total FROM employees 
                WHERE MONTH(join_date)=MONTH(CURRENT_DATE())
                AND YEAR(join_date)=YEAR(CURRENT_DATE())
            ")->total,

            'resignations' => DB::selectOne("
                SELECT COUNT(*) total FROM employees 
                WHERE resign_date IS NOT NULL
            ")->total,

            'pending_approvals' => DB::selectOne("
                SELECT COUNT(*) total FROM approvals WHERE status='pending'
            ")->total,

            'payroll_status' => DB::select("
                SELECT status, COUNT(*) total FROM payroll
                GROUP BY status
            "),

            'upcoming_birthdays' => DB::select("
                SELECT name, dob FROM employees
                WHERE DATE_FORMAT(dob, '%m-%d') >= DATE_FORMAT(CURDATE(), '%m-%d')
                ORDER BY MONTH(dob), DAY(dob)
                LIMIT 5
            ")
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function filter(Request $r)
    {
        $query = "
            SELECT * FROM employees WHERE 1=1
        ";

        $params = [];

        if ($r->branch) {
            $query .= " AND branch=?";
            $params[] = $r->branch;
        }

        if ($r->department) {
            $query .= " AND department=?";
            $params[] = $r->department;
        }

        if ($r->role) {
            $query .= " AND role=?";
            $params[] = $r->role;
        }

        $employees = DB::select($query, $params);

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }
}
