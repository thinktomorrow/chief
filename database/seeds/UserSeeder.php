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
        \App\User::create([
            'name'  => 'Think Tomorrow',
            'email' => 'info@thinktomorrow.be',
            'password' => bcrypt('kevin#emma')
        ]);

        \App\User::create([
            'name'  => 'Ben Cavens',
            'email' => 'ben@thinktomorrow.be',
            'password' => bcrypt('b3nc@v3ns')
        ]);

        \App\User::create([
            'name'  => 'Kevin Heylen',
            'email' => 'kevin@thinktomorrow.be',
            'password' => bcrypt('kevin#emma')
        ]);

        \App\User::create([
            'name'  => 'Johnny Berkmans',
            'email' => 'johnny@thinktomorrow.be',
            'password' => bcrypt('kevin#emma')
        ]);

        \App\User::create([
            'name'  => 'Philippe Damen',
            'email' => 'philippe@thinktomorrow.be',
            'password' => bcrypt('ph1l1pp3')
        ]);
    }

    protected function clientAccounts()
    {
        //
    }


}