<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Detalles: {{ $user->name }} {{ $user->lastname }}</h2>
                        <p class="mt-1 text-sm text-gray-600">Ver y/o editar detalles de acceso de este usuario.</p>
                        <p><a href="{{ route('staff.index') }}"><i class="bi bi-arrow-left"></i> volver al listado</a></p>
                    </header>

                    <form method="post" action="{{ route('staff.update', $user) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required value="{{ $user->name }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="lastname" :value="__('Lastname')" />
                            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" value="{{ $user->lastname }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ $user->email }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" name="password" type="text" class="mt-1 block w-full" placeholder="Si no desea cambiar el password dejar vacio" />
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>