<?php

namespace App\View\Components;

use App\Models\Loan;
use App\Models\User;
use Illuminate\View\Component;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class LoanStats extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $totalClients = User::where('type', 'client')->count();

        $fromDate = request()->get('from_date'); // Fecha de inicio obligatoria
        $toDate = request()->get('to_date') ?? now()->format('Y-m-d');
        $chartNumber = request()->get('chart');

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

        $lendedTotal = Loan::whereBetween('start_date', [now()->copy()->startOfYear(), now()])->sum('amount');
        // TODO: quizas necesito un campo del monto con el % de interes y asi sumo ese
        $returnedTotal = Loan::whereStatus('SETTLED')->whereBetween('start_date', [now()->copy()->startOfYear(), now()])->sum('amount');

        if ($fromDate) {
            $userToDate = $toDate ?? now();
            $lendedTotal = Loan::whereBetween('start_date', [$fromDate, $userToDate])->sum('amount');
        }

        $config = [
            'chart_title' => 'Dinero prestado por fechas',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Loan',
            'group_by_field' => 'start_date',
            'group_by_period' => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'chart_type' => 'line',
        ];

        if ($fromDate && $chartNumber == 3) {
            $config = array_merge($config, [
                'where_raw' => "created_at between '$fromDate' and '$toDate'",
            ]);
        }

        $chart3 = new LaravelChart($config);

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

        return view('components.loan-stats', compact('totalClients', 'chart1', 'chart2', 'chart3', 'chart4', 'lendedTotal', 'returnedTotal'));
    }
}
