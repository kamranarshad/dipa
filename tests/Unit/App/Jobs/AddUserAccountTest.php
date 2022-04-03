<?php

namespace Tests\Unit\App\Jobs;

use App;
use App\Jobs\AddUserAccount;
use App\Jobs\RetrievePendingTransactions;
use App\Jobs\RetrieveTransactions;
use App\Models\UserAccessToken;
use App\Supports\Services\TrueLayerService;
use Database\Seeders\ProviderSeeder;
use Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Queue;
use Tests\TestCase;

class AddUserAccountTest extends TestCase
{
    use RefreshDatabase;

    private TrueLayerService $service;
    private array $accountListResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->service = App::make(TrueLayerService::class);
        $this->accountListResponse = json_decode(file_get_contents(base_path('tests/Data/TrueLayer/Accounts.json')), true);

        UserAccessToken::factory()->create();
    }

    public function test_example()
    {
        Queue::fake();

        Http::fake([
            'api.truelayer-sandbox.com/data/v1/accounts' => Http::response($this->accountListResponse),
        ]);

        (new AddUserAccount(UserAccessToken::first(), $this->service->getAccounts(''), [1]))->handle();

        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseHas('accounts', [
            'code' => 'f1234560abf9f57287637624def390872'
        ]);

        Queue::assertPushed(RetrieveTransactions::class, 1);
        Queue::assertPushed(RetrievePendingTransactions::class, 1);
    }

    public function test_example_two()
    {
        Queue::fake();

        Http::fake([
            'api.truelayer-sandbox.com/data/v1/accounts' => Http::response($this->accountListResponse),
        ]);

        (new AddUserAccount(UserAccessToken::first(), $this->service->getAccounts(''), [0, 1]))->handle();

        $this->assertDatabaseCount('accounts', 2);

        Queue::assertPushed(RetrieveTransactions::class, 2);
        Queue::assertPushed(RetrievePendingTransactions::class, 2);
    }
}
