<?php

namespace app\Console\Commands;

use Chief\Roles\Role;
use Chief\Users\User;
use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    /**
     * @param $firstname
     * @param $lastname
     * @param $email
     * @param $password
     */
    protected function createUser($firstname, $lastname, $email, $password)
    {
        $user = new User;
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();

        $user->assignRole(Role::findByName('superadmin'));
    }

    /**
     * @return null|string
     * @throws \Exception
     */
    protected function askPassword()
    {
        $password = $passwordConfirm = null;
        $tries = 0;

        while (!$password || strlen($password) < 4 || $password != $passwordConfirm) {
            if ($tries > 2) {
                throw new \Exception('Aborting. Too many failed attempts to set password');
            }

            $password = $this->secret('Password');
            $passwordConfirm = $this->secret('Password (confirm)');

            $tries++;
        }

        return $password;
    }
}