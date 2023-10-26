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
                        <p class="mb-3 text-xs font-semibold"><a href="/loan/{{ $item->loan->uuid }}">Regresa al detalle de este préstamo</a></p>
                        <h2 class="text-lg font-medium text-gray-900">Pagaré</h2>
                        <p class="mt-1 text-sm text-gray-600">Detalles del pagaré del prestamo con UUID: {{ $item->loan->uuid ?? 'N/A' }}</p>
                    </header>

                    <div class="mt-6 space-y-6">
                        <p>Fecha generado: {{ $item->start_date }}</p>
                        <p>Fecha vencimiento: {{ $item->end_date }}</p>
                        <p>Cantidad a pagar: {{ $item->amount }}</p>
                        <p>Pagado el: <span class="badge bg-dark">{{ $item->paid_at ?? 'NO PAGADO' }}</span></p>


                        @if($item->hasMedia('payments'))
                            @php($mediaItems = $item->getMedia('payments'))

                            @foreach($mediaItems as $mediaItem)
                                <p><a target="_blank" href="{{ $mediaItem->getFullUrl() }}">{{ $mediaItem->name }}</a> - <a href="#" onclick="alert('funcion no disponible');"><i class="bi bi-trash3"></i></a></p>
                            @endforeach
                        @endif

{{--                    @if(! $item->paid_at && auth()->user()->type == 'admin')--}}
                        @if(! $item->paid_at)
                        <div class="pt-6">
                            <p class="mt-1 text-sm text-gray-600">Subir un comprobante de pago para marcar este pagaré como liquidado</p>
                            <form action="{{ route('loan.installment.storeNotePayment', ['installment' => $item]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <label for="file">Comprobante de pago</label>
                                    <input type="file" name="proof_of_payment" id="proof_of_payment">
                                </div>

                                <br>

                                <p>
                                    <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Subir</button>
                                </p>
                            </form>
                        </div>

                        <div class="pt-6">
                            <p class="mt-1 text-sm text-gray-600">Ó puedes marcar este pagaré como liquidado sin necesidad del comprobante.</p>
                            <form action="{{ route('loan.installment.storeNotePayment', ['installment' => $item]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="markaspaid" value="yes">
                                <p>
                                    <input type="checkbox" name="mark_as_paid" value="yes"> Si, deseo liquidar este pagaré sin comprobante.
                                </p>
                                <p>
                                    <button class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Pagado</button>
                                </p>
                            </form>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
