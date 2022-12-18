<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

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
            $user->addMedia($request->personal_id)
                ->usingName('personal_id')
                ->withCustomProperties([
                    'document_type' => 'personal_id',
                    'document_name' => 'INE, IFE o Pasaporte'
                ])
                ->toMediaCollection('documents');

            $user->addMedia($request->proof_of_address)
                ->usingName('proof_of_address')
                ->withCustomProperties([
                    'document_type' => 'proof_of_address', 
                    'document_name' => 'Comprobante domicilio'
                ])
                ->toMediaCollection('documents');

            // redirect to documentation module
            return redirect(route('client.edit', $user));
            
        } catch (Exception $exception) {
            return redirect()->back();
        }
    }

    public function destroy(User $user, string $type)
    {
        $mediaFiles = $user->getMedia('documents');

        foreach($mediaFiles as $media) {
            if ($media->name === $type) {
                $media->delete();
            }
        }
    }
}
