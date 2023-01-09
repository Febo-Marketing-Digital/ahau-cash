<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    public function index()
    {
        return view('staff.index', [
            'users' => User::where('type', 'staff')->get()
        ]);
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = 'staff';
        $user->birthdate = now();

        $user->save();

        return redirect(route('staff.index'));
    }

    public function show(User $user)
    {
        return view('staff.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
            'email' => ['email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
        ]);

        if (! empty($request->password)) {
            $request->validate([
                'password' => [Rules\Password::defaults()],
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name ?? $user->name;
        $user->lastname = $request->lastname ?? $user->name;
        $user->email = $request->email ?? $user->name;

        $user->save();

        return redirect(route('staff.index'));
    }

    public function delete(User $user)
    {
        $user->delete();

        return redirect(route('staff.index'));
    }
}