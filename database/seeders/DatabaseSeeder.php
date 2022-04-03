<?php

namespace Database\Seeders;

use App;
use App\Models\Provider;
use Illuminate\Database\Seeder;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        if (Provider::count() == 0)
            $this->call([ProviderSeeder::class]);

        if (! in_array(env('APP_ENV'), ['production', 'testing']))
        {
            $app = App::make(CreatesNewUsers::class);

            $app->create([
                'name'                  => 'John Doe',
                'email'                 => 'john.doe@mail.com',
                'password'              => 'password',
                'password_confirmation' => 'password',
            ]);
        }
    }
}
