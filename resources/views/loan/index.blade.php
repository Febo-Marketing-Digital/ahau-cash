<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">

                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Listado de prestamos</h2>
                        <p class="mt-1 text-sm text-gray-600">Listado de los prestamos actualmente vigentes</p>
                    </header>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>