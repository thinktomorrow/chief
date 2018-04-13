<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->thinktomorrowAccounts();
        $this->clientAccounts();
    }

    protected function thinktomorrowAccounts()
    {
        \Chief\Users\User::create([
            'name'  => 'Ben Cavens',
            'email' => 'ben@thinktomorrow.be',
            'password' => bcrypt(\Illuminate\Support\Str::random(8))
        ]);

        \Chief\Users\User::create([
            'name'  => 'Kevin Heylen',
            'email' => 'kevin@thinktomorrow.be',
            'password' => bcrypt(\Illuminate\Support\Str::random(8))
        ]);

        \Chief\Users\User::create([
            'name'  => 'Johnny Berkmans',
            'email' => 'johnny@thinktomorrow.be',
            'password' => bcrypt(\Illuminate\Support\Str::random(8))
        ]);

        \Chief\Users\User::create([
            'name'  => 'Philippe Damen',
            'email' => 'philippe@thinktomorrow.be',
            'password' => bcrypt(\Illuminate\Support\Str::random(8))
        ]);
    }

    protected function clientAccounts()
    {
        //
    }


}