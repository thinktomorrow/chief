<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\ManagedModels\States\State\State;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
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
            'state' => InvitationState::none->getValueAsString(),
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
        return URL::temporarySignedRoute('invite.accept', $this->expires_at, ['token' => $this->token]);
    }

    public function denyUrl(): string
    {
        return URL::temporarySignedRoute('invite.deny', $this->expires_at, ['token' => $this->token]);
    }

    public function getState(string $key): ?State
    {
        if (! $this->$key) {
            return null;
        }

        return InvitationState::from($this->$key);
    }

    public function changeState($key, State $state): void
    {
        $this->$key = $state->getValueAsString();
        $this->save();
    }

    public function getStateKeys(): array
    {
        return [InvitationState::KEY];
    }

    public function getStateConfig(string $stateKey): StateConfig
    {
        return new InvitationStateConfig();
    }

    public function present(): InvitationPresenter
    {
        return new InvitationPresenter($this);
    }

    public function inOnlineState(): bool
    {
        return $this->getState(InvitationState::KEY) == InvitationState::accepted;
    }

    public function scopeOnline(Builder $query): void
    {
        $query->where(InvitationState::KEY, InvitationState::accepted);
    }
}
