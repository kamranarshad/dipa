<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Budgets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @foreach($accounts as $account)

                @if($account->budgets->count() > 0)
                    <h2 class="mb-4">{{ $account->name }}</h2>

                    <div class="flex flex-col mb-4">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Classification</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($account->budgets as $b)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $b->classification->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">£{{ $b->amount }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                    <a href="{{ action([\App\Http\Controllers\BudgetController::class, 'edit'], [$b]) }}">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach

        </div>
    </div>

</x-app-layout>
