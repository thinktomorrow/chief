<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;
use Thinktomorrow\Chief\App\Notifications\ResetAdminPassword;
use Thinktomorrow\Chief\Shared\Concerns\Enablable;

class User extends Authenticatable implements HasAsset
{
    use Notifiable;
    use HasRoles;
    use AssetTrait;
    use Enablable;
    use HasFactory;

    public $table = 'chief_users';
    protected $guard_name = 'chief';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function findByEmail(string $email)
    {
        return self::where('email', $email)->first();
    }

    public function invitation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invitation::class, 'invitee_id');
    }

    public function roleNames()
    {
        return $this->roles->pluck('name')->toArray();
    }

    public function present(): UserPresenter
    {
        return new UserPresenter($this);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetAdminPassword($token));
    }

    public function getFullnameAttribute(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function isSquantoDeveloper(): bool
    {
        return $this->hasRole('developer');
    }

    public function getShortNameAttribute(): string
    {
        return $this->firstname . ' ' . substr($this->lastname, 0, 1) . '.';
    }
}
