<?php

namespace Tests\Unit\App\Http\Controllers;

use App;
use App\Http\Controllers\AccountController;
use App\Jobs\AddUserAccount;
use App\Jobs\AddUserCard;
use App\Models\User;
use App\Supports\Services\TrueLayerService;
use Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Queue;
use Session;
use Str;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private TrueLayerService $service;

    private array $cardListResponse;
    private array $accountListResponse;
    private array $accessTokenListResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(TrueLayerService::class);
        $this->accessTokenListResponse = json_decode(file_get_contents(base_path('tests/Data/TrueLayer/AccessToken.json')), true);
        $this->cardListResponse = json_decode(file_get_contents(base_path('tests/Data/TrueLayer/Cards.json')), true);
        $this->accountListResponse = json_decode(file_get_contents(base_path('tests/Data/TrueLayer/Accounts.json')), true);

        $this->actingAs($this->user = User::factory()->create());
    }

    public function test_create_method_in_controller_is_correctly_executed()
    {
        $token = Str::random();

        Session::put('truelayer.callback', ['code' => $token, 'scopes' => '']);

        Http::fake([
            'api.truelayer-sandbox.com/data/v1/cards'    => Http::response($this->cardListResponse),
            'api.truelayer-sandbox.com/data/v1/accounts' => Http::response($this->accountListResponse),
            'auth.truelayer-sandbox.com/connect/token'   => Http::response($this->accessTokenListResponse),
        ]);

        $cards = $this->service->getCards('');
        $accounts = $this->service->getAccounts('');

        $response = $this->get(action([AccountController::class, 'create']));

        $response->assertSessionHas('truelayer.cards', $cards);
        $response->assertSessionHas('truelayer.accounts', $accounts);
    }

    public function test_store_method_in_controller_is_correctly_executed()
    {
        Queue::fake();

        Http::fake([
            'api.truelayer-sandbox.com/data/v1/cards'    => Http::response($this->cardListResponse),
            'api.truelayer-sandbox.com/data/v1/accounts' => Http::response($this->accountListResponse),
            'auth.truelayer-sandbox.com/connect/token'   => Http::response($this->accessTokenListResponse),
        ]);

        Session::put([
            'truelayer' => [
                'exchange' => $this->service->getAccessToken(''),
                'accounts' => $this->service->getAccounts(''),
                'cards'    => $this->service->getCards(''),
            ]
        ]);

        $response = $this->post(action([AccountController::class, 'store']), [
            'cards'    => [0],
            'accounts' => [0],
        ]);

        $response->assertRedirect(action([AccountController::class, 'index']));

        Queue::assertPushed(AddUserAccount::class, 1);
        Queue::assertPushed(AddUserCard::class, 1);

        $this->assertDatabaseCount('user_access_tokens', 1);
    }
}
