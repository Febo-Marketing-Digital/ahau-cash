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
                        <p class="mt-1 text-sm text-gray-600">Creado {{ $user->created_at->diffForHumans() }} - Tiene 0 prestamos en su historial.</p>
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
                            <x-text-input id="phonenumber" name="phonenumber" type="text" class="mt-1 block w-full" value="{{ $phoneNumber->phonenumber }}" disabled />
                        </div>

                        <h3>Datos de domicilio</h3>

                        <div>
                            <p>Dirección: {{ $user->address->first()->street }} {{ $user->address->first()->house_number }}</p>
                            <p>{{ $user->address->first()->locality }} {{ $user->address->first()->province }} CDMX {{ $user->address->first()->postal_code }}</p>
                            <p><a href="#">Click acá para cambiar dirección</a></p>
                        </div>

                        <!-- <div>
                            <x-input-label for="street" :value="__('Street')" />
                            <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" value="{{ $user->address->first()->street }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('street')" />
                        </div>

                        <div>
                            <x-input-label for="house_number" :value="__('House Number')" />
                            <x-text-input id="house_number" name="house_number" type="text" class="mt-1 block" value="{{ $user->address->first()->house_number }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('house_number')" />
                        </div>

                        <div>
                            <x-input-label for="locality" :value="__('Locality')" />
                            <x-text-input id="locality" name="locality" type="text" class="mt-1 block w-full" value="{{ $user->address->first()->locality }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('locality')" />
                        </div>

                        <div>
                            <x-input-label for="province" :value="__('Province')" />
                            <x-text-input id="province" name="province" type="text" class="mt-1 block w-full" value="{{ $user->address->first()->province }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('province')" />
                        </div>

                        <div>
                            <x-input-label for="city" :value="__('City')" />
                            <select name="city" id="city">
                                <option value="CDMX" selected>Ciudad de México</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="postal_code" :value="__('Postal Code')" />
                            <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block" value="{{ $user->address->first()->postal_code }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                        </div> -->

                        <h3>Datos para depósito</h3>

                        @if($user->bankDetails)
                        <div>
                            <p>Beneficiario: {{ $user->bankDetails->first()->name }} {{ $user->bankDetails->first()->lastname }}</p>
                            <p>Banco y # de cuenta:{{ $user->bankDetails->first()->banl_name }} {{ $user->bankDetails->first()->account_number }}</p>
                            <p><a href="#">Click acá para cambiar datos bancarios</a></p>
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
                            <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full"  />
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