<div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Budgets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form action="{{ action([\App\Http\Controllers\BudgetController::class, 'store']) }}" method="POST">
                @csrf

                @include('dashboard.budget._form')

                <x-jet-button wire:click="submit" class="float-right mt-4">Save</x-jet-button>

            </form>

        </div>
    </div>

</div>
