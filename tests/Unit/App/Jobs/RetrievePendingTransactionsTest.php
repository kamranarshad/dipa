<?php

namespace Tests\Unit\App\Jobs;

use App;
use App\Jobs\RetrievePendingTransactions;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\UserAccessToken;
use App\Supports\Services\TrueLayerService;
use Database\Seeders\ProviderSeeder;
use Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetrievePendingTransactionsTest extends TestCase
{
    use RefreshDatabase;

    private array $transactionLists;

    protected function setUp(): void
    {
        parent::setUp();

        Account::unsetEventDispatcher();

        $this->seed();

        $this->service = App::make(TrueLayerService::class);
        $this->transactionLists = json_decode(file_get_contents(base_path('tests/Data/TrueLayer/PendingTransactions.json')), true);
    }

    public function test_correct_amount_database_rows_are_inserted_into_database_for_account_pending_transactions()
    {
        $user = UserAccessToken::factory()->create();

        $account = Account::factory()->create([
            'user_id'              => $user->user_id,
            'user_access_token_id' => $user->id,
        ]);

        Http::fake([
            "api.truelayer-sandbox.com/data/v1/accounts/$account->code/transactions/pending" => Http::response($this->transactionLists),
        ]);

        RetrievePendingTransactions::dispatch($account, App\Enums\TransactionType::Account);

        $this->assertDatabaseCount('transactions', 2);
        $this->assertEquals(2, Transaction::wherePending(true)->count());

        $this->assertDatabaseCount('classifications', 4);
        $this->assertDatabaseCount('transaction_classifications', 4);
    }
}
