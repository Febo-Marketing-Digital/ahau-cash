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

                    <form method="post" action="{{ route('client.update.address', $user) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        @include('user.partials/address-form')

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>