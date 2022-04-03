<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Budgets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @include('shared.error')

            <form action="{{ action([\App\Http\Controllers\BudgetController::class, 'update'], [$budget]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="classification" class="block text-sm font-medium text-gray-700">Classification</label>
                    <select wire:model="classification" id="classification" name="classification" autocomplete="classification-name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option>Please Select</option>
                        @foreach($classifications as $classification)
                            <option value="{{ $classification->id }}" {{ $budget->classification->id == $classification->id ? 'selected' : '' }}>{{ $classification->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"> £ </span>
                        <input type="text" name="amount" id="amount" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" value="{{ $budget->amount }}">
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-jet-button class="float-right">Save</x-jet-button>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>
