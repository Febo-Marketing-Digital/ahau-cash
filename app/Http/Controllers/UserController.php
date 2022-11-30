<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserBankDetail;
use App\Models\UserPhonenumber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index', [
            'clients' => User::where('type', '=', 'client')->get(),
        ]);
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        ]);

        try {
            DB::beginTransaction();
            
            $user = User::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'email' => $request->email,
                'gender' => $request->gender,
                'password' => bcrypt('secret'),
                'type' => 'client',
            ]);

            $phoneNumberType = strtoupper($request->phonenumber_type);

            UserPhonenumber::create([
                'user_id' => $user->id,
                'phonenumber' => $request->phonenumber,
                'type' => $phoneNumberType,
                'is_main' => true,
            ]);

            UserAddress::create([
                'user_id' => $user->id,
                'street' => $request->street,
                'house_number' => $request->house_number,
                'locality' => $request->locality,
                'province' => $request->province,
                'city' => $request->city,
                'state' => 'N/A', //$request->state,
                'postal_code' => $request->postal_code
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            return redirect()->back();
        }

        // redirect to documentation module
        return redirect(route('documentation.edit', ['user' => $user->id]));
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {

        //dd($request, $user);

        UserBankDetail::create([
            'user_id' => $user->id,
            'name' => $request->account_owner_name,
            'lastname' => $request->account_owner_lastname,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'clabe' => $request->clabe,
        ]);

        return redirect(route('client.index'));
    }
}
