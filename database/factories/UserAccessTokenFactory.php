<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class UserAccessTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'       => User::factory(),
            'status'        => '',
            'token'         => Str::random(),
            'refresh_token' => Str::random(),
            'expired_at'    => Carbon::now()->addSeconds(3600),
            'scopes'        => [],
        ];
    }
}
