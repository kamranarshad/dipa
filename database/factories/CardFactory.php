<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $provider = Provider::whereCode('mock')->first();

        return [
            'provider_id'          => $provider->id,
            'user_id'              => '',
            'user_access_token_id' => '',
            'code'                 => Str::random(),
            'network'              => '',
            'type'                 => 'TRANSACTION',
            'description'          => '',
            'last_4'               => '5112',
            'name'                 => 'Club Lloyds',
            'valid_from'           => '',
            'valid_to'             => '',
        ];
    }
}
