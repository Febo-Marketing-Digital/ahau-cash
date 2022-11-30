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
        $latestLoans = Loan::latest()->get();
        $totalClients = User::where('type', 'client')->count();

        return view('dashboard', compact('latestLoans', 'totalClients'));
    }
}
