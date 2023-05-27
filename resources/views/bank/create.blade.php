<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Banks') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Nuevo Banco</h2>
                        <p class="mt-1 text-sm text-gray-600">Agrega un nuevo Banco a la lista existente, estará disponible en todos los listados.</p>
                    </header>

                    <form method="post" action="{{ route('bank.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="display_name" :value="__('Bank Name')" />
                            <x-text-input id="display_name" name="display_name" type="text" class="mt-1 block w-full" required autofocus autocomplete="display_name" />
                            <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
                        </div>

                        <div>
                            <x-input-label for="lastname" :value="__('Activo')" />
                            <input type="checkbox" class="" value="1" name="is_active" checked>
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
