<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clients') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">{{ $user->name }} {{ $user->lastname }}</h2>
                        <p class="mt-1 text-sm text-gray-600">Creado {{ $user->created_at->diffForHumans() }} - Tiene <strong>{{ $user->loans->count() }} prestamos</strong> en su historial.</p>
                        <p><a href="{{ route('client.index') }}"><i class="bi bi-arrow-left"></i> volver al listado</a></p>
                    </header>

                    <form method="post" action="{{ route('client.update', $user) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ $user->name }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="lastname" :value="__('Lastname')" />
                            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" value="{{ $user->lastname }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
                        </div>

                        <div>
                            <x-input-label for="gender" :value="__('Gender')" />
                            
                            <input type="radio" name="gender" id="male" value="M" @if($user->gender == 'M') checked @endif>
                            <label for="male">Masculino</label><br>
                            <input type="radio" id="female" name="gender" value="F" @if($user->gender == 'F') checked @endif>
                            <label for="female">Femenino</label><br>  
                            <br>
                        </div>

                        <div>
                            <x-input-label for="birthdate" :value="__('Birthdate')" />
                            <x-text-input id="birthdate" name="birthdate" type="date" class="mt-1 block" value="{{ $user->birthdate }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ $user->email }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            @php($phoneNumber = $user->phonenumbers->first())
                            <x-input-label for="phonenumber" :value="__('Phonenumber')" />
                            <x-text-input id="phonenumber" name="phonenumber" type="text" class="mt-1 block w-full" value="{{ $phoneNumber->phonenumber }}" />
                        </div>

                        <h3>Datos de domicilio</h3>

                        @if($user->address)
                            <div>
                                <p>Dirección: {{ $user->address->completeAddress() }}</p>
                                <p><a  href="{{ route('client.edit.address', $user) }}">Click acá para cambiar dirección</a></p>
                            </div>
                        @else
                            @include('user.partials/address-form')
                        @endif

                        <h3>Datos para depósito</h3>

                        @if($user->bankDetails)
                        <div>
                            <p>Beneficiario: {{ $user->bankDetails->name }} {{ $user->bankDetails->lastname }}</p>
                            <p>Banco y Nº de cuenta: <strong>{{ $user->bankDetails->bank_name }}</strong> - <strong>{{ $user->bankDetails->account_number }}</strong></p>
                            <p><a href="{{ route('client.edit.bankDetails', $user) }}">Click acá para cambiar datos bancarios</a></p>
                        </div>
                        @else

                        <div>
                            <x-input-label for="account_owner_name" :value="__('Account Owner Name')" />
                            <x-text-input id="account_owner_name" name="account_owner_name" type="text" class="mt-1 block w-full" value="{{ $user->name }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('account_owner_name')" />
                        </div>

                        <div>
                            <x-input-label for="account_owner_lastname" :value="__('Account Ownser Lastname')" />
                            <x-text-input id="account_owner_lastname" name="account_owner_lastname" type="text" class="mt-1 block w-full" value="{{ $user->lastname }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('account_owner_lastname')" />
                        </div>


                        <div>
                            <x-input-label for="bank_name" :value="__('Bank name')" />
                            <select name="bank_name" id="bank_name">
                                <option value="" selected>Selecciona a un banco</option>
                                @foreach($banks as $key => $bank)
                                    <option value="{{ $key }}">{{ $bank }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('bank_name')" />
                        </div>


                        <div>
                            <x-input-label for="account_number" :value="__('Account Number')" />
                            <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full"  />
                            <x-input-error class="mt-2" :messages="$errors->get('account_number')" />
                        </div>

                        <div>
                            <x-input-label for="clabe" :value="__('CLABE')" />
                            <x-text-input id="clabe" name="clabe" type="text" class="mt-1 block w-full"  />
                            <x-input-error class="mt-2" :messages="$errors->get('clabe')" />
                        </div>

                        @endif

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>