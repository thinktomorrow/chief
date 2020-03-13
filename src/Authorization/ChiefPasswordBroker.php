<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Authorization;

use Illuminate\Auth\Passwords\PasswordBroker;

/** @inheritDoc */
class ChiefPasswordBroker extends PasswordBroker
{
    const RESET_LINK_SENT = 'chief::passwords.sent';
    const PASSWORD_RESET = 'chief::passwords.reset';
    const INVALID_USER = 'chief::passwords.user';
    const INVALID_PASSWORD = 'chief::passwords.password';
    const INVALID_TOKEN = 'chief::passwords.token';
}
