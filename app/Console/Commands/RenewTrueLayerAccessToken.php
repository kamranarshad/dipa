<?php

namespace App\Console\Commands;

use App\Models\UserAccessToken;
use App\Supports\Services\TrueLayerService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RenewTrueLayerAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truelayer:renew_access_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TrueLayer Renew Access Token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TrueLayerService $service)
    {
        // get all access token going to expire in 10 minutes
        $userAccessTokens = UserAccessToken::where('expired_at', '<=', Carbon::now()->subMinutes(50)->toDateTimeString())
            ->get();

        collect($userAccessTokens)->each(function (UserAccessToken $userAccessToken) use ($service) {
            $accessToken = $service->renewAccessToken($userAccessToken->refresh_token);

            // update database with new access token and refresh token
            $userAccessToken->update([
                'token'         => $accessToken->accessToken,
                'refresh_token' => $accessToken->refreshToken,
                'expired_at'    => Carbon::now()->addSeconds($accessToken->expiresIn)->format('Y-m-d H:i:s'),
            ]);
        });

        return 0;
    }
}
