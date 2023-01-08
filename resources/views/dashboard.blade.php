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
                    
                    {{-- <p> Prestamos totales: # {{ $totalLoans }} / $ {{ $totalLoansAmount }}<p>
                    <p> Prestamos activos: # {{ $totalActiveLoans }} / $ {{ $totalActiveLoansAmount }}<p>
                    <p> Ganancias: $ {{ $earnings }} </p> --}}

                    <!-- <h1>{{ $chart1->options['chart_title'] }}</h1> -->
                    {!! $chart1->renderHtml() !!}
                    <!-- <h1>{{ $chart2->options['chart_title'] }}</h1> -->
                    {!! $chart2->renderHtml() !!}

                    {!! $chart3->renderHtml() !!}

                    <p>Total clientes: {{ $totalClients }}</p>

                    {!! $chart4->renderHtml() !!}
                </div>
            </div>
        </div>
    </div>

    {!! $chart1->renderChartJsLibrary() !!}
    {!! $chart1->renderJs() !!}
    {!! $chart2->renderJs() !!}
    {!! $chart3->renderJs() !!}
    {!! $chart4->renderJs() !!}
</x-app-layout>
