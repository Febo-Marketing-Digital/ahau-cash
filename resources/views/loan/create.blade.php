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
                        <h2 class="text-lg font-medium text-gray-900">Nuevo prestamo</h2>
                        <p class="mt-1 text-sm text-gray-600">Crear un nuevo prestamo para el cliente seleccionado</p>
                    </header>

                    <form method="post" action="{{ route('loan.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="loan_type" :value="__('Loan type')" />
                            <select name="loan_type" id="loan_type">
                                @if($clients->count() > 1)
                                <option value="" selected>Selecciona a un tipo</option>
                                <option value="PERSONAL">Personal</option>
                                <option value="GROUP">Grupal</option>
                                @else
                                <option value="PERSONAL" selected>Personal</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <x-input-label for="client" :value="__('Client')" />
                            <select name="client" id="client">
                                @if($clients->count() > 1)
                                <option value="" selected>Selecciona a un cliente</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->fullname() }}</option>
                                @endforeach
                                @else
                                    @php($client = $clients->first())
                                    <option value="{{ $client->id }}" selected>{{ $client->fullname() }}</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <x-input-label for="amount" :value="__('Loan amount')" />
                            <x-text-input id="amount" name="amount" type="text" class="mt-1 block w-full" placeholder="$" />
                        </div>

                        <div>
                            <x-input-label for="start_date" :value="__('Loan start date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block" required autofocus autocomplete="start_date" />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>

                        <div>
                            <x-input-label for="installment_period" :value="__('Installment Period')" />
                            <select name="installment_period" id="installment_period">
                                <option value="MONTHLY" selected>Mensual</option>
                                <option value="BIWEEKLY">Quincenal</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="installment_quantity" :value="__('Installment Qty')" />
                            <x-text-input id="installment_quantity" name="installment_quantity" type="text" class="mt-1 block" />
                        </div>

                        <div>
                            <x-input-label for="loan_roi" :value="__('Loan ROI')" />
                            <x-text-input id="loan_roi" name="loan_roi" type="text" class="mt-1 block" />
                        </div>

                        <!-- <div>
                            <x-input-label for="duration_unit" :value="__('Loan duration')" />
                            <x-text-input id="duration_unit" name="loan_duration[unit]" type="text" class="mt-1 block" />
                        </div>

                        <div>
                            <x-input-label for="duration_period" :value="__('Loan period')" />
                            <select name="duration_period['period']" id="duration_period">
                                <option value="WEEK" selected>SEMANA</option>
                                <option value="MONTH">MES</option>
                                <option value="YEAR">AÑO</option>
                            </select>
                        </div> -->

                        <div>
                            <x-input-label for="require_documentation" :value="__('Require update documents')" />
                            <input type="checkbox" name="require_documentation" id="require_documentation" value="yes">
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