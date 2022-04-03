<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Budgets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @include('shared.error')

            <form action="{{ action([\App\Http\Controllers\BudgetController::class, 'store']) }}" method="POST">
                @csrf

                @include('dashboard.budget._form')

                <div class="flex items-center justify-end mt-4">
                    <x-jet-button class="float-right">Save</x-jet-button>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>
