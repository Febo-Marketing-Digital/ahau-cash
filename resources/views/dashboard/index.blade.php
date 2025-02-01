<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        @can('view all stats')
        <x-loan-stats />
        @else
        <p>TBD</p>
        @endcan
    </div>
</x-app-layout>
