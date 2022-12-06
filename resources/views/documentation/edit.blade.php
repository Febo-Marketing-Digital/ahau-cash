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
                        <p class="mt-1 text-sm text-gray-600">Sube los documentos del cliente necesarios para la solicitud del prestamo.</p>
                    </header>

                    <div class="mt-4">
                        @if($user->hasMedia('documents'))
                            @php($mediaItems = $user->getMedia('documents'))

                            @foreach($mediaItems as $mediaItem)
                                <p><a href="{{ $mediaItem->getFullUrl() }}">{{ $mediaItem->name }}</a> - <a href="#" onclick="alert('funcion no disponible');"><i class="bi bi-trash3"></i></a></p>
                            @endforeach
                        @endif

                        <form action="{{ route('documentation.update', $user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div>
                                <label for="file">Documento de identificacion</label>
                                <input type="file" name="personal_id" id="personal_id">
                            </div>
                            <!-- <div>
                                <label for="file">Comprobante de situacion fiscal</label>
                                <input type="file" name="tax_id" id="tax_id">
                            </div> -->
                            <div>
                                <label for="file">Comprobante de domicilio</label>
                                <input type="file" name="proof_of_address" id="proof_of_address">
                            </div>

                            <br>

                            <p>
                                <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Subir</button>
                            </p>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>