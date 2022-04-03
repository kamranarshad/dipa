<?php

namespace Tests\Unit\App\Http\Controllers;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TrueLayerController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class TrueLayerControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user = User::factory()->create());
    }

    public function test_redirect_and_session_data_when_post_data_is_passed()
    {
        $token = Str::random();
        $scope = Str::random();

        $response = $this->post(action(TrueLayerController::class), [
            'code'  => $token,
            'scope' => $scope,
        ]);

        $response->assertRedirect(action([AccountController::class, 'create']));
        $response->assertSessionHas('truelayer.callback', ['code' => $token, 'scope' => $scope]);
    }

    public function test_redirect_with_error_message_when_no_post_data_is_passed()
    {
        $response = $this->post(action(TrueLayerController::class));

        $response->assertRedirect(action([AccountController::class, 'index']));
        $response->assertSessionMissing('truelayer.callback');
        $response->assertSessionHas('error', 'Could not auth with bank provider');
    }
}
