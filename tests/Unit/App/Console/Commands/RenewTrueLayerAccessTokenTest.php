<?php

namespace Tests\Unit\App\Console\Commands;

use App;
use App\Models\User;
use App\Models\UserAccessToken;
use App\Supports\Services\TrueLayerService;
use Carbon\Carbon;
use Http;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RenewTrueLayerAccessTokenTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private TrueLayerService $service;

    private array $renewAccessTokenListResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(TrueLayerService::class);

        $this->renewAccessTokenListResponse = json_decode(file_get_contents(base_path('tests/Data/TrueLayer/RenewAccessToken.json')), true);
    }

    public function access_token_has_been_updated()
    {
        UserAccessToken::unsetEventDispatcher();

        UserAccessToken::factory()->create();

        Http::fake([
            'auth.truelayer-sandbox.com/connect/token' => Http::response($this->renewAccessTokenListResponse),
        ]);

        $this->artisan('truelayer:renew_access_token')->assertExitCode(0);

        $this->assertDatabaseHas('user_access_tokens', [
            'token'         => $this->renewAccessTokenListResponse['access_token'],
            'refresh_token' => $this->renewAccessTokenListResponse['refresh_token']
        ]);
    }

    public function test_correct_amount_of_access_token_has_been_updated()
    {
        UserAccessToken::unsetEventDispatcher();

        UserAccessToken::factory()
            ->count(2)
            ->state(new Sequence(
                ['expired_at' => Carbon::now()->subMinutes(55)],
                ['expired_at' => Carbon::now()->addHours(5)]
            ))
            ->create();

        Http::fake([
            'auth.truelayer-sandbox.com/connect/token' => Http::response($this->renewAccessTokenListResponse),
        ]);

        $this->artisan('truelayer:renew_access_token')->assertExitCode(0);

        $this->assertDatabaseHas('user_access_tokens', [
            'token'         => $this->renewAccessTokenListResponse['access_token'],
            'refresh_token' => $this->renewAccessTokenListResponse['refresh_token']
        ]);

        $userAccessTokenCount = UserAccessToken::where([
            'token'         => $this->renewAccessTokenListResponse['access_token'],
            'refresh_token' => $this->renewAccessTokenListResponse['refresh_token'],
        ])->count();

        $this->assertEquals(1, $userAccessTokenCount);
    }
}
