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

        $totalClients = User::where('type', 'client')->count();

        $fromDate = $request->get('from_date'); // Fecha de inicio obligatoria
        $toDate = $request->get('to_date') ?? now()->format('Y-m-d');
        $chartNumber = $request->get('chart');

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

        $whereRaw = "";
        if ($fromDate && $chartNumber == 3) {
            $whereRaw = "created_at between '$fromDate' and '$toDate'";
        }
        // TODO: create this array for parametres dinamic
        $chart3 = new LaravelChart([
            'chart_title' => 'Dinero prestado por fechas',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Loan',
            'where_raw' => $whereRaw,
            'group_by_field' => 'created_at',
            'group_by_period' => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'chart_type' => 'line',
        ]);

        $whereRaw = "type = 'client'";
        if ($fromDate && $chartNumber == 4) {
           $whereRaw = "type = 'client' and created_at between '$fromDate' and '$toDate'";
        }

        $chart4 = new LaravelChart([
            'chart_title' => 'Usuarios en rango',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\User',
            'where_raw' => $whereRaw,
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
            'filter_field' => 'created_at',
            'filter_days' => 365, // show only last 30 days
        ]);
//        $chart4 = new LaravelChart([
//            'chart_title' => 'Clientes registrados',
//            'report_type' => 'group_by_date',
//            'model' => 'App\Models\User',
//            'group_by_field' => 'created_at',
//            'group_by_period' => 'month',
//            'aggregate_function' => 'sum',
//            'aggregate_field' => 'id',
//            'chart_type' => 'line',
//            'filter_days' => 365, // show only last 30 days
//        ]);

        return view('dashboard',
            compact( 'totalClients', 'chart1', 'chart2', 'chart3', 'chart4')
        );
    }
}
