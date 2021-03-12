<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

class Invitation extends Model implements StatefulContract
{
    public $guarded = [];

    protected $dates = ['expires_at'];

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
            'state' => InvitationState::NONE,
            'token' => $token,
            'expires_at' => now()->addMinutes($expires ?? self::$expires),
        ]);
    }

    public static function findByToken(string $token)
    {
        return self::where('token', $token)->first();
    }

    public function invitee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }

    public function inviter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function acceptUrl(): string
    {
        return URL::temporarySignedRoute('invite.accept',            $this->expires_at,            ['token' => $this->token]);
    }

    public function denyUrl(): string
    {
        return URL::temporarySignedRoute('invite.deny',            $this->expires_at,            ['token' => $this->token]);
    }

    public function stateOf(string $key): string
    {
        return $this->$key;
    }

    /**
     * @return void
     */
    public function changeStateOf($key, $state)
    {
        $this->$key = $state;
        $this->save();
    }

    public function present(): InvitationPresenter
    {
        return new InvitationPresenter($this);
    }
}
