<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loans') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-4">
            <a href="{{ route('loan.create') }}" class="btn btn-secondary focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nuevo préstamo
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-auto">

                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Listado de prestamos</h2>
                        <p class="mt-1 text-sm text-gray-600">Listado de los prestamos actualmente vigentes</p>
                    </header>

                    <div class="mt-4">

                        @if(session("message"))
                            <div class="py-4 px-6 rounded {{ session('class') }} text-white">
                                {{ session('message') }}
                            </div>
                        @endif

                        <div class="">
                            <form action="">
                                <x-text-input
                                    name="name"
                                    type="text"
                                    class="mt-1 block w-3/4"
                                    placeholder="Buscar por cliente..."
                                />
                                <br>

                                <div class="flex">
                                    <x-text-input
                                        name="from_start_date"
                                        type="date"
                                        class="mt-1 flex-1 w-1/2 px-2"
                                        placeholder="Buscar por fecha de inicio..."
                                    />

                                    <x-text-input
                                        name="tp_start_date"
                                        type="date"
                                        class="mt-1 flex-1 w-1/2 mx-2"
                                        placeholder="Buscar por fecha de inicio..."
                                    />
                                </div>


                                <div class="mt-3 block w-auto">
                                    <x-primary-button>{{ __('Filter') }}</x-primary-button>

                                    <a href="{{ route('loan.index') }}">Limpiar filtro</a>
                                </div>
                            </form>
                        <div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-person"></i> Cliente</th>
                                    <th><i class="bi bi-list"></i> Tipo</th>
                                    <th><i class="bi bi-cash-coin"></i> Cant. Prestamo</th>
                                    <th><i class="bi bi-bank"></i> % Interés</th>
                                    <th><i class="bi bi-cash-stack"></i> Cant. a devolver</th>
                                    <th><i class="bi bi-calendar-check"></i> Periocidad cuotas</th>
                                    <th><i class="bi bi-check2-square"></i> Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loans as $loan)
                                <tr>
                                    <td>
                                        @if(auth()->user()->type == 'admin' && $loan->status == 'PENDING')
                                            <i class="bi bi-circle-fill text-warning"></i>
                                        @elseif(auth()->user()->type == 'staff' && $loan->status == 'PENDING')
                                            <i class="bi bi-circle-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-check-circle text-success"></i>
                                        @endif
                                        {{ $loan->user->fullname() }}
                                    </td>
                                    <td>{{ $loan->type }}</td>
                                    <td>{{ $loan->formattedAmount() }}</td>
                                    <td>{{ $loan->roi }} %</td>
                                    <td>{{ $loan->amountToReturn() }}</td>
                                    <td>{{ __($loan->installment_period) }}</td>
                                    <td><span class="badge bg-{{ $loan->loanPaymentStatus()[1] }}">{{ $loan->loanPaymentStatus()[0] }}</span></td>
                                    <td>
                                        <a class="btn btn-dark" title="Ver detalles del prestamo" href="{{ route('loan.show', $loan->uuid) }}"><i class="bi bi-eye-fill"></i></a>
                                        <a class="btn btn-danger" title="Eliminar préstamo" href="{{ route('loan.delete', $loan) }}" onclick="return confirm('Seguro que desea eliminar este préstamo?');"><i class="bi bi-trash3"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8">
                                        {{ $loans->links() }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
