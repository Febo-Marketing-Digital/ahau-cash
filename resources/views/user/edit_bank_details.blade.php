<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clients') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">{{ $user->name }} {{ $user->lastname }}</h2>
                        <p class="mt-1 text-sm text-gray-600">Ultima actualización de datos fue el {{ $user->updated_at->diffForHumans() }}</p>
                        <p><a href="{{ route('client.edit', $user) }}"><i class="bi bi-arrow-left"></i> volver a los detalles</a></p>
                    </header>

                    <form method="post" action="{{ route('client.update.bankDetails', $user) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="account_owner_name" :value="__('Account Owner Name')" />
                            <x-text-input id="account_owner_name" name="account_owner_name" type="text" class="mt-1 block w-full" value="{{ $user->name }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('account_owner_name')" />
                        </div>

                        <div>
                            <x-input-label for="account_owner_lastname" :value="__('Account Ownser Lastname')" />
                            <x-text-input id="account_owner_lastname" name="account_owner_lastname" type="text" class="mt-1 block w-full" value="{{ $user->lastname }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('account_owner_lastname')" />
                        </div>


                        <div>
                            <x-input-label for="bank_name" :value="__('Bank name')" />
                            <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full" value="{{ $user->bankDetails?->first()->bank_name }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('bank_name')" />
                        </div>


                        <div>
                            <x-input-label for="account_number" :value="__('Account Number')" />
                            <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full" value="{{ $user->bankDetails?->first()->account_number }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('account_number')" />
                        </div>

                        <div>
                            <x-input-label for="clabe" :value="__('CLABE')" />
                            <x-text-input id="clabe" name="clabe" type="text" class="mt-1 block w-full" value="{{ $user->bankDetails?->first()->clabe }}"  />
                            <x-input-error class="mt-2" :messages="$errors->get('clabe')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>