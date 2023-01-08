<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (auth()->user()->type == 'staff') {
            return view('dashboard_staff' );
        }
        
        $earnings = 0; // Ganancia, total en intereses
        
        $totalClients = User::where('type', 'client')->count();
        
        // $totalLoans = Loan::count();
        // $totalActiveLoans = Loan::where('status', 'APPROVED')->count();

        // $totalLoansAmount = Loan::where('status', 'APPROVED')->sum('amount');
        // $totalActiveLoansAmount = Loan::where('status', 'APPROVED')->sum('amount');

        $chart1 = new LaravelChart([
            'chart_title' => 'Préstamos al mes',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Loan',
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
        ]);

        $chart2 = new LaravelChart([
            'chart_title' => 'Préstamos aprobados al mes',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Loan',
            'where_raw' => "status = 'APPROVED'",
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
        ]);
        
        $chart3 = new LaravelChart([
            'chart_title' => 'Dinero prestado por fechas',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Loan',
            'group_by_field' => 'created_at',
            'group_by_period' => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'chart_type' => 'line',
        ]);

        $chart4 = new LaravelChart([
            'chart_title' => 'Usuarios ultimos 30 dias',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\User',
            'where_raw' => "type = 'client'",
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
            'filter_field' => 'created_at',
            'filter_days' => 30, // show only last 30 days
        ]);

        return view('dashboard', 
            compact('earnings', 'totalClients', 'chart1', 'chart2', 'chart3', 'chart4')
        );
    }
}
