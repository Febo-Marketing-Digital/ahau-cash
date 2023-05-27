<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::all();
        $banksSorted = $banks->sortBy('display_name');

        return view('bank.index', [
            'banks' => $banksSorted
        ]);
    }
    public function create()
    {
        return view('bank.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
        ]);

        $bank = new Bank();

        $bank->display_name = $request->display_name;
        $bank->code = Str::slug($request->display_name);
        $bank->is_active = $request->is_active;

        $bank->save();

        return redirect(route('bank.index'));
    }

    public function delete(Bank $bank)
    {
        $bank->delete();

        return redirect(route('bank.index'));
    }
}
