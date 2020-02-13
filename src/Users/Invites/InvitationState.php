<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Users\Invites;

use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\States\State\StateMachine;

class InvitationState extends StateMachine
{
    const KEY = 'state';

    /**
     * Possible states of the invitation process:
     *
     * NONE - no invitation sent; nothing has happened so far
     * PENDING - invitation sent or resent and awaiting acceptance
     * EXPIRED - invitation is no longer valid due to the time restriction
     * ACCEPTED - new user has accepted the invite
     * DENIED - new user has denied the invite explicitly
     * REVOKED - invitation is revoked by an admin
     */
    const NONE = 'none';
    const PENDING = 'pending';
    const EXPIRED = 'expired';
    const ACCEPTED = 'accepted';
    const DENIED = 'denied';
    const REVOKED = 'revoked';

    protected $states = [
        self::NONE,
        self::PENDING,
        self::EXPIRED,
        self::REVOKED,
        self::ACCEPTED,
        self::DENIED,
    ];

    protected $transitions = [
        'invite' => [
            'from' => [self::NONE, self::EXPIRED, self::REVOKED],
            'to'   => self::PENDING,
        ],
        'expire' => [
            'from' => [self::PENDING],
            'to'   => self::EXPIRED,
        ],
        'revoke' => [
            'from' => [self::PENDING, self::EXPIRED],
            'to'   => self::REVOKED,
        ],
        'accept' => [
            'from' => [self::PENDING],
            'to'   => self::ACCEPTED,
        ],
        'deny'   => [
            'from' => [self::PENDING],
            'to'   => self::DENIED,
        ],
    ];

    public static function make(StatefulContract $model)
    {
        return new static($model, static::KEY);
    }
}
