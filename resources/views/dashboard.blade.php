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

                    <!-- <h1>{{ $chart1->options['chart_title'] }}</h1> -->
                    <h3 class="text-center">Prestamos creados</h3>
                    {!! $chart1->renderHtml() !!}
                    <!-- <h1>{{ $chart2->options['chart_title'] }}</h1> -->
                    <h3 class="text-center">Prestamos aprobados</h3>
                    {!! $chart2->renderHtml() !!}

                    <h3 class="text-center">Dinero prestado</h3>

                    <div class="">
                        <form action="">
                            <x-text-input
                                name="from_date"
                                type="date"
                                class="mt-1 block w-3/4"
                                placeholder="desde"
                            />

                            <x-text-input
                                name="to_date"
                                type="date"
                                class="mt-1 block w-3/4"
                                placeholder="hasta"
                            />

                            <x-text-input
                                name="chart"
                                type="hidden"
                                value="3"
                            />

                            <div class="mt-3 block w-auto">
                                <x-primary-button>{{ __('Filtrar') }}</x-primary-button>

                                <a href="{{ route('dashboard') }}">Limpiar filtro</a>
                            </div>
                        </form>
                    <div>

                    {!! $chart3->renderHtml() !!}

                    <h3 class="text-center">Clientes registrados</h3>

                    <div class="">
                        <form action="">
                            <x-text-input
                                name="from_date"
                                type="date"
                                class="mt-1 block w-3/4"
                                placeholder="desde"
                            />

                            <x-text-input
                                name="to_date"
                                type="date"
                                class="mt-1 block w-3/4"
                                placeholder="hasta"
                            />

                            <x-text-input
                                name="chart"
                                type="hidden"
                                value="4"
                            />

                            <div class="mt-3 block w-auto">
                                <x-primary-button>{{ __('Filtrar') }}</x-primary-button>

                                <a href="{{ route('dashboard') }}">Limpiar filtro</a>
                            </div>
                        </form>
                     <div>

                    {!! $chart4->renderHtml() !!}
                    <p class="text-center">Total clientes: {{ $totalClients }}</p>
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
