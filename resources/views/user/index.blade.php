<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clients') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Listado de clientes</h2>
                        <p class="mt-1 text-sm text-gray-600">Listado de todos los clientes registrados con o sin creditos activos.</p>
                    </header>

                    <div class="mt-4">
                        <table class="table-auto">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>E-mail</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)                        
                                <tr>
                                    <td>{{ $client->name }} {{ $client->lastname }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>
                                        <a href="{{ route('client.edit', $client) }}">[EDIT]</a>
                                        <a href="{{ route('documentation.edit', $client) }}">[DOCS]</a>
                                        <a href="{{ route('loan.create', ['client_id' => $client->id]) }}">[NEW LOAN]</a>
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