<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loans') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-4">
            <a href="{{ route('loan.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                                    <td>{{ $loan->installment_period }}</td>
                                    <td><span class="badge bg-{{ $loan->loanPaymentStatus()[1] }}">{{ $loan->loanPaymentStatus()[0] }}</span></td>
                                    <td>
                                        <a class="btn btn-dark" title="Ver detalles del prestamo" href="{{ route('loan.show', $loan->uuid) }}"><i class="bi bi-eye-fill"></i></a>
                                        <a class="btn btn-danger" title="Eliminar préstamo" href="{{ route('loan.delete', $loan) }}" onclick="confirm('Seguro que desea eliminar este préstamo?');"><i class="bi bi-trash3"></i></a>
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