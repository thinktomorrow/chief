<?php

namespace App\Console\Commands;

class CreateAdmin extends BaseCommand
{
    protected $signature = 'chief:create-admin';
    protected $description = 'Create a new chief admin user';

    public function handle()
    {
        $anticipations = $this->getAnticipations();

        $firstname = $this->anticipate('firstname',array_pluck($anticipations,'firstname'));
        $anticipatedLastname = null;
        $lastname = $this->anticipate('lastname',array_pluck($anticipations,'lastname'),$anticipatedLastname);

        $email = $this->ask('email',str_slug($firstname).'@thinktomorrow.be');

        $password = $this->askPassword();

        $this->createUser($firstname, $lastname, $email, $password);

        $this->info($firstname.' '.$lastname. ' succesfully added as admin user.');
    }

    /**
     * We assume we are creating users for ourselves so we make this a bit easier to do
     * @return array
     */
    private function getAnticipations()
    {
        $anticipations = [
            [
                'firstname' => 'Ben',
                'lastname'  => 'Cavens',
                'email'     => 'ben@thinktomorrow.be',
            ],
            [
                'firstname' => 'Philippe',
                'lastname'  => 'Damen',
                'email'     => 'philippe@thinktomorrow.be',
            ],
            [
                'firstname' => 'Johnny',
                'lastname'  => 'Berkmans',
                'email'     => 'johnny@thinktomorrow.be',
            ],
            [
                'firstname' => 'Bob',
                'lastname'  => 'Dries',
                'email'     => 'bob@thinktomorrow.be',
            ],
        ];

        return $anticipations;
    }
}
