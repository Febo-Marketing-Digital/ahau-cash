<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Total clientes: {{ $totalClients }}</p>

                    <p> Prestamos totales (cantidad y cuanto es en dinero)<p>
                    <p> Prestamos activos (cantidad y cuanto es en dinero)<p>
                    <p> Ganancias (total de intereses) </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
