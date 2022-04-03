<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid gap-6 md:grid-cols-3 lg:grid-cols-4 mt-7">
                @each('dashboard.provider._provider', $providers, 'provider')
            </div>

        </div>
    </div>
</x-app-layout>
