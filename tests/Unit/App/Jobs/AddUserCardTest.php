<?php

namespace Tests\Unit\App\Jobs;

use App;
use App\Jobs\AddUserAccount;
use App\Jobs\AddUserCard;
use App\Jobs\RetrievePendingTransactions;
use App\Jobs\RetrieveTransactions;
use App\Models\UserAccessToken;
use App\Supports\Services\TrueLayerService;
use Database\Seeders\ProviderSeeder;
use Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Queue;
use Tests\TestCase;

class AddUserCardTest extends TestCase
{
    use RefreshDatabase;

    private TrueLayerService $service;
    private array $cardListResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->service = App::make(TrueLayerService::class);
        $this->cardListResponse = json_decode(file_get_contents(base_path('tests/Data/TrueLayer/Cards.json')), true);

        UserAccessToken::factory()->create();
    }

    public function test_example()
    {
        Queue::fake();

        Http::fake([
            'api.truelayer-sandbox.com/data/v1/cards' => Http::response($this->cardListResponse),
        ]);

        (new AddUserCard(UserAccessToken::first(), $this->service->getCards(''), [1]))->handle();

        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseHas('accounts', [
            'code' => 'ad86fb1c213245b6b6594895068973efca5f9367'
        ]);

        Queue::assertPushed(RetrieveTransactions::class, 1);
        Queue::assertPushed(RetrievePendingTransactions::class, 1);
    }

    public function test_example_two()
    {
        Queue::fake();

        Http::fake([
            'api.truelayer-sandbox.com/data/v1/cards' => Http::response($this->cardListResponse),
        ]);

        (new AddUserCard(UserAccessToken::first(), $this->service->getCards(''), [0, 1]))->handle();

        $this->assertDatabaseCount('accounts', 2);

        Queue::assertPushed(RetrieveTransactions::class, 2);
        Queue::assertPushed(RetrievePendingTransactions::class, 2);
    }
}
