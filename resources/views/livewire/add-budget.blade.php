<div>
    <x-modal formAction="update">
        <x-slot name="title">
            Add Budget
        </x-slot>

        <x-slot name="content">
            <div class="col-span-6 sm:col-span-3">
                <label for="classification" class="block text-sm font-medium text-gray-700">Classification</label>
                <select wire:model="classification" id="classification" name="classification" autocomplete="classification-name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option>Please Select</option>
                    @foreach($classifications as $classification)
                        <option value="{{ $classification->id }}">{{ $classification->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-6">
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"> £ </span>
                    <input wire:model="amount" type="text" name="amount" id="amount" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                </div>
            </div>
        </x-slot>

        <x-slot name="buttons">
            <button wire:click="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
        </x-slot>
    </x-modal>
</div>
