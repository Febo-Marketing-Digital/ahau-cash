<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-10xl">

                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Préstamo {{ $loan->uuid }}</h2>
                        <p class="mt-1 text-sm text-gray-600">Detalles del préstamo otorgado a: {{ $loan->user->name }} {{ $loan->user->lastname }}</p>
                    </header>

                    <div class="mt-6 space-y-6">

                        <div class="container">
                            <div class="row align-items-start">
                                <div class="col">
                                    <p>Préstamo solicitado: {{ $loan->formattedAmount() }}</p>
                                    <p>Cantidad de devolver: {{ $loan->amountToReturn() }} ({{ $loan->roi }} %)</p>
                                </div>
                                <div class="col">
                                    {!!   $loan->getMediaLink() !!}
                                </div>
                                <div class="col">
                                    <form method="post" action="{{ route('loan.settled', $loan) }}">
                                        @csrf
                                        <button class="btn btn-primary"><i class="bi bi-bookmark-check"></i> Liquidar Préstamo</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if($loan->status === 'PENDING' && auth()->user()->type == 'admin')
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">PENDIENTE DE APROBACIÓN</h5>
                                <p class="card-text">Este prestamo fue creado por {{ $loan->staff->name . ' ' . $loan->staff->lastname }} y necesita aprobación.</p>

                                <div class="container text-center">
                                    <div class="row align-items-start">
                                        <div class="col">
                                            <form method="post" action="{{ route('loan.update', $loan) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="APPROVED">
                                                <button class="btn btn-success">Aprobar Préstamo</button>
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form method="post" action="{{ route('loan.update', $loan) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="DECLINED">
                                                <button class="btn btn-danger">Declinar Préstamo</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Inicio</th>
                                    <th>Vencimiento</th>
                                    <th>Cantidad</th>
                                    <th>Balance pendiente</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->installments as $key => $installment)
                                <tr @if(! is_null($installment->paid_at)) style="background-color: #7ec17e; color: white;" @endif>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $installment->start_date->format('d/m/Y') }}</td>
                                    <td>{{ $installment->end_date->format('d/m/Y') }}</td>
                                    <td>{{ $installment->formattedAmount() }}</td>
                                    <td>{{ $installment->formattedBalance() }}</td>
                                    <td>
                                        {!! $installment->getMediaLink() !!}
                                    </td>
                                    <td>
                                        <a class="btn btn-dark" title="detalles del pagaré" href="{{ route('loan.installment.show', $installment->id) }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
