<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $account->name . __(' Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid gap-6 md:grid-cols-3 lg:grid-cols-4 mb-6">
                @foreach($topClassifications as $classification)
                    <div class="flex flex-col mb-6">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-t-lg">

                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ $classification->name }}
                                            </th>
                                        </thead>
                                        <tbody class="bg-white">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-2xl justify-start">
                                                    £ {{ $budgets->where('classification_id', $classification->id)->sum('amount') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                    @if($budgets->where('classification_id', $classification->id)->sum('amount') >= $classification->balance)
                                                        <span class="text-green-500">&#x25B2</span>
                                                    @else
                                                        <span class="text-red-500">&#x25BC</span>
                                                    @endif
                                                    £ {{ $classification->balance }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <h2 class="font-medium text-gray-900 mb-4">Pending Transactions</h2>

            <div class="flex flex-col mb-6">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-t-lg">

                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merchant Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @each('dashboard.transaction._transaction', $pendingTransactions, 'transaction')
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

            <h2 class="font-medium text-gray-900 mb-4">Transactions</h2>

            <div class="flex flex-col mb-6">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-t-lg">

                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merchant Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @each('dashboard.transaction._transaction', $transactions, 'transaction')
                                </tbody>
                            </table>

                        </div>

                        <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-b-lg px-6 py-3">
                            {{ $transactions->links() }}
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
