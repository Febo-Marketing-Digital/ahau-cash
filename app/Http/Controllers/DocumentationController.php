<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPhonenumber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentationController extends Controller
{
    public function edit(User $user)
    {
        return view('documentation.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'personal_id' => ['required', 'file'],
            'proof_of_address' => ['required', 'file'],
        ]);

        try {

            // TODO: If already exists a document, override

            $user->addMedia($request->personal_id)
                ->usingName('personal_id')
                ->toMediaCollection('documents');
            // $user->addMedia($request->tax_id)
            //     ->usingName('tax_id')
            //     ->toMediaCollection('documents');
            $user->addMedia($request->proof_of_address)
                ->usingName('proof_of_address')
                ->toMediaCollection('documents');
            

        } catch (Exception $exception) {

            dd($exception->getMessage());

            return redirect()->back();
        }

        // redirect to documentation module
        return redirect(route('client.edit', $user));
    }

}
