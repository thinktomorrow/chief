<?php

namespace Thinktomorrow\Chief\Users\Invites;

use Thinktomorrow\Chief\Common\State\StatefulContract;
use Thinktomorrow\Chief\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Invitation extends Model implements StatefulContract
{
    public $guarded = [];

    /**
     * Minutes from now that invitation will expire.
     *
     * @var int
     */
    private static $expires = 60 * 24 * 3;

    public static function make(string $invitee_id, string $inviter_id, $expires = null)
    {
        $token = InvitationToken::generate();

        return self::create([
            'invitee_id' => $invitee_id,
            'inviter_id' => $inviter_id,
            'state'      => 'none',
            'token'      => $token,
            'expires_at' => now()->addMinutes($expires ?? self::$expires),
        ]);
    }

    public static function findByToken(string $token)
    {
        return self::where('token',$token)->first();
    }

    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function acceptUrl()
    {
        return URL::signedRoute(
            'invite.accept', ['token' => $this->token], $this->expires_at
        );
    }

    public function denyUrl()
    {
        return URL::signedRoute(
            'invite.deny', ['token' => $this->token], $this->expires_at
        );
    }

    public function state(): string
    {
        return $this->state;
    }

    public function changeState($state)
    {
        $this->state = $state;
        $this->save();
    }

    public function present()
    {
        return new InvitationPresenter($this);
    }
}