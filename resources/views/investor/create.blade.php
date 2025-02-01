<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Investors') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">

                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Nuevo Inversionista</h2>
                        <p class="mt-1 text-sm text-gray-600">Ingrese todos los datos para crear un nuevo inversionista en Ahau Cash</p>
                    </header>


                    <form method="post" action="{{ route('investor.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="lastname" :value="__('Lastname')" />
                            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" required autofocus autocomplete="lastname" />
                            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
                        </div>

                        <div>
                            <x-input-label for="gender" :value="__('Gender')" />

                            <input type="radio" name="gender" id="male" value="M">
                            <label for="male">Masculino</label><br>
                            <input type="radio" id="female" name="gender" value="F">
                            <label for="female">Femenino</label><br>
                            <br>
                        </div>

                        <div>
                            <x-input-label for="birthdate" :value="__('Birthdate')" />
                            <x-text-input id="birthdate" name="birthdate" type="date" class="mt-1 block" required autofocus autocomplete="birthdate" />
                            <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" autocomplete="email" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" name="password" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <div>
                            <x-input-label for="phonenumber" :value="__('Phonenumber')" />
                            <x-text-input id="phonenumber" name="phonenumber" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('phonenumber')" />
                        </div>

                        <div>
                            <input type="radio" name="phonenumber_type" id="phonenumber_type_mobile" value="mobile">
                            <label for="age1">Celular</label><br>
                            <input type="radio" id="phonenumber_type_home" name="phonenumber_type" value="home">
                            <label for="age2">Casa</label><br>
                            <input type="radio" id="phonenumber_type_office" name="phonenumber_type" value="office">
                            <label for="age3">Oficina</label><br><br>
                        </div>

                        <h3>Datos de domicilio</h3>

                        <div>
                            <x-input-label for="street" :value="__('Street')" />
                            <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('street')" />
                        </div>

                        <div>
                            <x-input-label for="house_number" :value="__('House Number')" />
                            <x-text-input id="house_number" name="house_number" type="text" class="mt-1 block" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('house_number')" />
                        </div>

                        <div>
                            <x-input-label for="locality" :value="__('Locality')" />
                            <x-text-input id="locality" name="locality" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('locality')" />
                        </div>

                        <div>
                            <x-input-label for="province" :value="__('Province')" />
                            <x-text-input id="province" name="province" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('province')" />
                        </div>

                        <div>
                            <x-input-label for="city" :value="__('City')" />
                            <select name="city" id="city">
                                <option value="CDMX" selected>Ciudad de México</option>
                                <option value="EDOMX">Estado de México</option>
                                <option value="PACHU">Pachuca</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="state" :value="__('State')" />
                            <select name="state" id="state">
                                <option value="CDMX" selected>Ciudad de México</option>
                                <option value="EDOMX">Estado de México</option>
                                <option value="HIDAL">Hidalgo</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="postal_code" :value="__('Postal Code')" />
                            <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
