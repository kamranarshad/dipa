<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\Card;
use App\Models\Provider;
use App\Models\UserAccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddUserCard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private UserAccessToken $userAccessToken, private array $cardList, private array $cardsSelected)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->userAccessToken->user;

        collect($this->cardList)->each(function ($item, $key) use ($user) {
            if (! in_array($key, $this->cardsSelected))
            {
                return;
            }

            $provider = Provider::firstWhere('code', $item->provider);

            Account::create([
                'provider_id'          => $provider->id,
                'user_id'              => $user->id,
                'user_access_token_id' => $this->userAccessToken->id,
                'code'                 => $item->code,
                'network'              => $item->network,
                'type'                 => $item->type,
                'description'          => $item->description,
                'last_4'               => $item->lastFour,
                'name'                 => $item->name,
                'valid_from'           => $item->validFrom,
                'valid_to'             => $item->validTo,
            ]);
        });
    }
}
