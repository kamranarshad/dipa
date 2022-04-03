<tr>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
                @if(! is_null($transaction->name))
                    <img class="h-10 w-10" src="{{ $transaction->logo }}" alt="">
                @endif
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">
                    {{ ucwords(strtolower($transaction->name ?? $transaction->description)) }}
                </div>
                <div class="whitespace-nowrap">
                    @foreach($transaction->classifications as $classification)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ $classification->name ?? null }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $transaction->payment_at->format('d/m/Y') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if ($transaction->type == 'CREDIT')
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                £{{ $transaction->amount }}
            </span>
        @else
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                £{{ $transaction->amount }}
            </span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $transaction->running_balance ?? null }}
    </td>
</tr>
