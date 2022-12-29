<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;

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
        $earnings = 0; // Ganancia, total en intereses
        
        $totalClients = User::where('type', 'client')->count();
        
        $totalLoans = Loan::count();
        $totalActiveLoans = Loan::where('status', 'APPROVED')->count();

        $totalLoansAmount = Loan::where('status', 'APPROVED')->sum('amount');
        $totalActiveLoansAmount = Loan::where('status', 'APPROVED')->sum('amount');

        return view('dashboard', compact('earnings', 'totalClients', 'totalLoans', 'totalActiveLoans', 'totalLoansAmount', 'totalActiveLoansAmount'));
    }
}
