<?php

namespace Modules\Acc\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Acc\Entities\Setting;
use Modules\Acc\Entities\User;
use Modules\Acc\Entities\Userlink;

class AccDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Create the TestUser for Login
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ]);

        // Create 10 more TestUsers
        User::factory(10)->create();

        // Create the Links for all Testusers
        foreach (User::all() as $user) {
            for ($i=0; $i < rand(1,9); $i++)
            {
                Userlink::factory()->create([
                    'user_id'   =>  $user->id,
                ]);
            }

        }

        // Create the Website Settings
        Setting::factory(1)->create();
    }
}
