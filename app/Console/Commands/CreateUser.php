<?php

namespace App\Console\Commands;

use Chief\Roles\Role;
use Chief\Users\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $anticipations = $this->getAnticipations();

        $firstname = $this->anticipate('firstname',array_pluck($anticipations,'firstname'));
        $anticipatedLastname = null;
        $lastname = $this->anticipate('lastname',array_pluck($anticipations,'lastname'),$anticipatedLastname);

        $email = $this->ask('email',str_slug($firstname).'@thinktomorrow.be');

        $password = $passwordConfirm = null;
        $tries = 0;

        while(!$password || strlen($password) < 4 || $password != $passwordConfirm)
        {
            if($tries > 2)
            {
                $this->error('Aborting. Too many attempts to set password');
                return false;
            }

            $password = $this->secret('Wachtwoord');
            $passwordConfirm = $this->secret('Wachtwoord (confirm)');

            $tries++;
        }

        $user = new User;
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();

        $user->assignRole(Role::findByName('Superadmin'));

        $this->info($firstname.' '.$lastname. ' succesfully added as admin user.');
    }

    /**
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
