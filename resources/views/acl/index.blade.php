<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles y Permisos') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-4">
            <a href="{{ route('acl.role.create') }}" class="btn btn-primary">
                Nuevo Rol
            </a>

            <a href="{{ route('acl.permission.create') }}" class="btn btn-primary">
                Nuevo Permiso
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-5xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Listado de Roles y permisos</h2>
                        <p class="mt-1 text-sm text-gray-600">Listado de todos los roles disponibles en el sistema y sus permisos asociados.</p>
                    </header>

                    <div class="mt-4">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><i class="bi bi-bank"></i> Permiso</th>
                                @foreach($roles as $role)
                                <th><i class="bi bi-circle"></i> {{ $role->name  }}</th>
                                @endforeach
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <form action="{{ route('acl.update.permissions', $permission) }}" method="POST">
                                        @method('PUT')
                                        @csrf
                                    <td>{{ $permission->name }}</td>
                                    @foreach($roles as $role)
                                        @if($role->name == 'Super-Admin')
                                        <td><input type="checkbox" class="checkbox checkbox-secondary" checked id="check-{{ $role->name }}" /></th>
                                        @else
                                        <td><input type="checkbox" class="checkbox checkbox-primary" name="{{ $role->name }}" value="1" @if($role->hasPermissionTo($permission->name)) checked @endif id="check-{{ $role->name }}" /></th>
                                        @endif
                                    @endforeach
                                    <td>
                                        @can('update permissions')
                                        <button class="btn btn-secondary" type="submit">Actualizar</button>
                                        @else
                                        <a href="#" class="btn btn-ghost">Actualizar</a>
                                        @endcan
                                    </td>
                                    </form>
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
