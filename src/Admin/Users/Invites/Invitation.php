<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\ManagedModels\States\State\State;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

/**
 * @property string $token
 * @property int $invitee_id
 * @property int|null $inviter_id
 * @property array{id: int, firstname: string, lastname: string, fullname: string, email: string|null} $inviter_snapshot
 * @property string $state
 * @property Carbon $expires_at
 */
class Invitation extends Model implements StatefulContract
{
    /**
     * Minutes from now that invitation will expire.
     *
     * @var int
     */
    private static $expires = 60 * 24 * 3;

    public $guarded = [];

    protected $table = 'chief_users_invitations';

    protected $casts = [
        'expires_at' => 'datetime',
        'inviter_snapshot' => 'array',
    ];

    public static function make(User $invitee, User $inviter, ?int $expires = null): self
    {
        $token = InvitationToken::generate();

        return self::create([
            'invitee_id' => $invitee->id,
            'inviter_id' => $inviter->id,
            'inviter_snapshot' => [
                'id' => (int) $inviter->id,
                'firstname' => $inviter->firstname,
                'lastname' => $inviter->lastname,
                'fullname' => $inviter->fullname,
                'email' => $inviter->email,
            ],
            'state' => InvitationState::none->getValueAsString(),
            'token' => $token,
            'expires_at' => now()->addMinutes($expires ?? self::$expires),
        ]);
    }

    public static function findByToken(string $token)
    {
        return self::where('token', $token)->first();
    }

    public function invitee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function inviterFirstname(): string
    {
        return $this->inviter_snapshot['firstname'];
    }

    public function acceptUrl(): string
    {
        return URL::temporarySignedRoute('invite.accept', $this->expires_at, ['token' => $this->token]);
    }

    public function denyUrl(): string
    {
        return URL::temporarySignedRoute('invite.deny', $this->expires_at, ['token' => $this->token]);
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
        return new InvitationStateConfig;
    }

    public function present(): InvitationPresenter
    {
        return new InvitationPresenter($this);
    }

    public function inOnlineState(): bool
    {
        return $this->getState(InvitationState::KEY) == InvitationState::accepted;
    }

    public function getState(string $key): ?State
    {
        if (! $this->$key) {
            return null;
        }

        return InvitationState::from($this->$key);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where(InvitationState::KEY, InvitationState::accepted);
    }
}
