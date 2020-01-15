<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Users;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Thinktomorrow\Chief\Concerns\Enablable;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\App\Notifications\ResetAdminPassword;

class User extends Authenticatable implements HasAsset
{
    use Notifiable, HasRoles, AssetTrait, Enablable;

    public $table = 'chief_users';
    protected $guard_name = 'chief';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function findByEmail(string $email)
    {
        return self::where('email', $email)->first();
    }

    public function invitation()
    {
        return $this->hasMany(Invitation::class, 'invitee_id');
    }

    public function roleNames()
    {
        return $this->roles->pluck('name')->toArray();
    }

    public function present()
    {
        return new UserPresenter($this);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetAdminPassword($token));
    }

    public function getFullnameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function isSquantoDeveloper()
    {
        return $this->hasRole('developer');
    }

    public function getShortNameAttribute()
    {
        return $this->firstname . ' ' . substr($this->lastname, 0, 1) . '.';
    }
}
