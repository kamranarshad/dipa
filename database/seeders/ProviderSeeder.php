<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Provider::count() == 0)
        {
            $response = file_get_contents("https://auth.truelayer.com/api/providers");
            $providers = json_decode($response, true);

            collect($providers)->map(function ($item) {
                Provider::create([
                    'code'    => $item['provider_id'],
                    'name'    => $item['display_name'],
                    'country' => $item['country'],
                    'logo'    => $item['logo_url'],
                    'scopes'  => $item['scopes'],
                ]);
            });
        }
    }
}
