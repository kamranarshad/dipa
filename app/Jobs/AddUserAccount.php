<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\Provider;
use App\Models\UserAccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddUserAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private UserAccessToken $userAccessToken, private array $accountList, private array $accountsSelected)
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

        collect($this->accountList)->each(function ($item, $key) use ($user) {
            if (! in_array($key, $this->accountsSelected))
            {
                return;
            }

            $provider = Provider::firstWhere('code', $item->provider);

            Account::create([
                'provider_id'          => $provider->id,
                'user_id'              => $user->id,
                'user_access_token_id' => $this->userAccessToken->id,
                'code'                 => $item->code,
                'type'                 => $item->type,
                'name'                 => $item->name,
                'number'               => $item->number,
                'sort_code'            => $item->sortCode,
            ]);
        });
    }
}
