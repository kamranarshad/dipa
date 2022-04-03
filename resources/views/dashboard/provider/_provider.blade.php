<div class="bg-white rounded-lg divide-y divide-gray-200 shadow flex flex-col">
    <div class="flex-grow px-6 py-4 space-y-2 w-full">
        <img src="{{ $provider->logo }}" alt="ksassnowski" loading="lazy" class="mx-auto flex-shrink-0 bg-white h-10 justify-center my-4">
        <div class="flex justify-between items-center space-x-6 w-full">
            <div class="flex-1 truncate">
                <h3 class="space-x-1 text-base font-normal text-gray-500 hover:text-gray-700 text-center">
                    {{ $provider->name }}
                </h3>
            </div>
        </div>
        <p class="text-sm text-gray-500 text-center">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus quia, nulla! Maiores et perferendis eaque, exercitationem praesentium nihil
        </p>
    </div>
    <div class="flex -mt-px w-full divide-x divide-gray-200">
        <a href="{{ action([\App\Http\Controllers\ProviderController::class, 'show'], ['provider' => $provider]) }}" class="inline-flex relative flex-grow justify-center items-center py-4 px-3 -mr-px text-sm font-medium rounded-bl-lg group  flex-1 " title="GitHub">
            <span class="ml-3 text-gray-700 group-hover:text-gray-500 divide-y divide-gray-200">Connect</span>
        </a>
    </div>
</div>
