<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserBankDetail;
use App\Models\UserPhonenumber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $clients = User::query();
        $clients->with('phonenumbers');

        if ($filter = $request->get('name')) {
            $clients->where(function ($query) use ($filter) {
               $query->where('name', 'like', '%'.$filter.'%')
                   ->orWhere('lastname', 'like', '%'.$filter.'%');
            });
        }

        $clients = $clients->where('type', '=', 'client')
            ->orderBy('name')
            ->paginate(25);

        //$sortedResult = $clients->getCollection()->sortBy('name')->values();
        //$clients->setCollection($sortedResult);

        return view('user.index', compact('clients'));
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
            'email' => ['string', 'email', 'max:255', 'unique:'.User::class],
            'phonenumber' => ['digits:10'],
            'gender' => ['required', 'in:M,F'],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'email' => $request->email ?? "",
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
                'state' => $request->city == 'CMDX' ? 'Ciudad de México' : 'México',
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
        if (auth()->user()->type == 'staff') {
            return redirect(route('client.index'));
        }

        $activeBanks = Bank::where('is_active', 1)->get();
        $banks = $activeBanks->sortBy('display_name');

        return view('user.edit', compact('user', 'banks'));
    }

    public function update(User $user, Request $request)
    {
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->birthdate = $request->birthdate;

        $user->save();

        $currentPhone = $user->phonenumbers->first();
        if (! empty($request->phonenumber) && $currentPhone->phonenumber <> $request->phonenumber) {
            $userPhoneNumber = UserPhonenumber::where('phonenumber', $currentPhone->phonenumber)->first();
            $userPhoneNumber->phonenumber = $request->phonenumber;
            $userPhoneNumber->save();
        }

        $bankDetails = UserBankDetail::where('user_id', $user->id)->first();

        if (! $bankDetails) {
            $bank = Bank::where('code', $request->bank_name)->first();

            UserBankDetail::create([
                'user_id' => $user->id,
                'name' => $request->account_owner_name,
                'lastname' => $request->account_owner_lastname,
                'bank_name' => $bank->display_name,
                'account_number' => $request->account_number,
                'clabe' => $request->clabe,
            ]);
        }

        $userAddress = UserAddress::where('user_id', $user->id)->first();

        if (! $userAddress) {
            UserAddress::create([
                'user_id' => $user->id,
                'street' => $request->street,
                'house_number' => $request->house_number,
                'locality' => $request->locality,
                'province' => $request->province,
                'city' => $request->city,
                'state' => get_state_by_city($request->city),
                'postal_code' => $request->postal_code
            ]);
        }

        return redirect(route('client.index'));
    }

    public function delete(User $user)
    {
        $user->delete();

        return redirect(route('client.index'));
    }

    public function editAddress(User $user)
    {
        return view('user.edit_address', compact('user'));
    }

    public function editBankDetails(User $user)
    {
        $activeBanks = Bank::where('is_active', 1)->get();
        $banks = $activeBanks->sortBy('display_name');

        return view('user.edit_bank_details', compact('user', 'banks'));
    }

    public function updateAddress(User $user, Request $request)
    {
        $userAddress = UserAddress::where('user_id', $user->id)->first();

        $userAddress->street = $request->street;
        $userAddress->house_number = $request->house_number;
        $userAddress->locality = $request->locality;
        $userAddress->city = $request->city;
        $userAddress->postal_code = $request->postal_code;

        $userAddress->save();

        return redirect(route('client.index'));
    }

    public function updateBankDetails(User $user, Request $request)
    {
        $bankDetails = UserBankDetail::where('user_id', $user->id)->first();

        $bank = Bank::where('code', $request->bank_name)->first();

        $bankDetails->bank_name = $bank->display_name;
        $bankDetails->account_number = $request->account_number;
        $bankDetails->clabe = $request->clabe;

        $bankDetails->save();

        return redirect(route('client.index'));
    }
}
