<?php

namespace Tests\Unit\App\Jobs;

use App;
use App\Enums\TransactionType;
use App\Jobs\RetrieveTransactions;
use App\Models\Account;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\UserAccessToken;
use App\Supports\Services\TrueLayerService;
use Database\Seeders\ProviderSeeder;
use Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetrieveTransactionsTest extends TestCase
{
    use RefreshDatabase;

    private array $transactionLists;
    private Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->service = App::make(TrueLayerService::class);
        $this->transactionLists = json_decode(file_get_contents(base_path('tests/Data/TrueLayer/Transactions.json')), true);
    }

    public function test_correct_amount_database_rows_are_inserted_into_database_for_account_transactions()
    {
        Account::unsetEventDispatcher();

        $user = UserAccessToken::factory()->create();

        $account = Account::factory()->create([
            'user_id'              => $user->user_id,
            'user_access_token_id' => $user->id,
        ]);

        Http::fake([
            "api.truelayer-sandbox.com/data/v1/accounts/$account->code/transactions" => Http::response($this->transactionLists),
        ]);

        RetrieveTransactions::dispatch($account, TransactionType::Account);

        $this->assertDatabaseCount('transactions', 2);
        $this->assertEquals(2, Transaction::wherePending(false)->count());

        $this->assertDatabaseCount('classifications', 4);
        $this->assertDatabaseCount('transaction_classifications', 4);
    }

    public function test_correct_amount_database_rows_are_inserted_into_database_for_card_transactions()
    {
        Card::unsetEventDispatcher();

        $user = UserAccessToken::factory()->create();

        $card = Account::factory()->create([
            'user_id'              => $user->user_id,
            'user_access_token_id' => $user->id,
            'type'                 => 'CREDIT',
        ]);

        Http::fake([
            "api.truelayer-sandbox.com/data/v1/cards/$card->code/transactions" => Http::response($this->transactionLists),
        ]);

        RetrieveTransactions::dispatch($card, TransactionType::Card);

        $this->assertDatabaseCount('transactions', 2);
        $this->assertEquals(2, Transaction::wherePending(false)->count());

        $this->assertDatabaseCount('classifications', 4);
        $this->assertDatabaseCount('transaction_classifications', 4);
    }
}
