<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clients') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-4">
            <a href="{{ route('client.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nuevo cliente
            </a>
        </div>
    
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Listado de clientes</h2>
                        <p class="mt-1 text-sm text-gray-600">Listado de todos los clientes registrados con o sin creditos activos.</p>
                    </header>

                    <div class="mt-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-person"></i>  Nombre Completo</th>
                                    <th><i class="bi bi-envelope-at"></i> E-mail</th>
                                    <th><i class="bi bi-calendar"></i> Cliente desde</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)                        
                                <tr>
                                    <td>{{ $client->name }} {{ $client->lastname }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a class="btn btn-dark" title="Ver o editar detalles" href="{{ route('client.edit', $client) }}"><i class="bi bi-person-fill-exclamation"></i></a>
                                        <a class="btn btn-primary" title="Documentacion"href="{{ route('documentation.edit', $client) }}"><i class="bi bi-person-rolodex"></i></a>
                                        <a class="btn btn-success" title="Nuevo prestamo" href="{{ route('loan.create', ['client_id' => $client->id]) }}"><i class="bi bi-cash-coin"></i></a>
                                        <a class="btn btn-danger" href="#" onclick="alert('funcion no disponible');"><i class="bi bi-trash3"></i></a>
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