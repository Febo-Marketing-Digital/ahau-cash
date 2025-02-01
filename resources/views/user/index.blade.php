<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clients') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-4">
            <a href="{{ route('client.create') }}" class="btn btn-secondary focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nuevo cliente
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">

                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Listado de clientes</h2>
                        <p class="mt-1 text-sm text-gray-600">Listado de todos los clientes registrados con o sin creditos activos.</p>
                    </header>

                    <div class="mt-4">

                        <div class="">
                            <form action="">
                                <x-text-input
                                    name="name"
                                    type="text"
                                    class="mt-1 block w-3/4"
                                    placeholder="Buscar por nombre..." />

                                <div class="mt-3 block w-auto">
                                    <x-primary-button>{{ __('Filter') }}</x-primary-button>

                                    <a href="{{ route('client.index') }}">Limpiar filtro</a>
                                </div>
                            </form>
                            <div>

                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-person"></i> Nombre Completo</th>
                                            <th><i class="bi bi-envelope-at"></i> E-mail</th>
                                            <th><i class="bi bi-phone"></i> Teléfono</th>
                                            <th><i class="bi bi-calendar"></i> Cliente desde</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($clients as $client)
                                        <tr>
                                            <td>{{ $client->name }} {{ $client->lastname }}</td>
                                            <td>{{ $client->email }}</td>
                                            <td>{{ $client->phonenumbers->first()?->phonenumber }}</td>
                                            <td>{{ $client->created_at->diffForHumans() }}</td>
                                            <td>
                                                @if(auth()->user()->type == 'admin')
                                                <a class="btn btn-dark" title="Ver o editar detalles" href="{{ route('client.edit', $client) }}"><span class="iconify lucide--edit"></span></a>
                                                @endif
                                                <a class="btn btn-primary" title="Documentacion" href="{{ route('documentation.edit', $client) }}"><span class="iconify lucide--notepad-text"></span></a>
                                                @if(is_null($client->address) or is_null($client->bankDetails))
                                                <a class="btn btn-mute" title="Nuevo prestamo" href="#" onclick="alert('Cliente con informacion incompleta.');"><span class="iconify lucide--file-warning"></span></a>
                                                @else
                                                <a class="btn btn-success" title="Nuevo prestamo" href="{{ route('loan.create', ['client_id' => $client->id]) }}"><span class="iconify lucide--circle-dollar-sign"></span></a>
                                                @endif
                                                @can('delete client')
                                                <a class="btn btn-danger" title="Eliminar cliente" href="{{ route('client.delete', $client) }}" onclick="confirm('Seguro que desea eliminar este registro?');">
                                                    <span class="iconify lucide--trash-2"></span>
                                                </a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5">
                                                {{ $clients->links() }}
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
