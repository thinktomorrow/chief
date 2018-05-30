<?php

namespace Thinktomorrow\Chief\Users;

use Thinktomorrow\Chief\App\Notifications\ResetAdminPassword;
use Thinktomorrow\Chief\Common\Traits\Enablable;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Users\Invites\InvitationState;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, HasMediaTrait, Enablable;

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
        return $this->hasOne(Invitation::class, 'invitee_id');
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
        return $this->isSuperAdmin();
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('developer');
    }

    public function getShortNameAttribute()
    {
        return $this->firstname . ' ' . substr($this->lastname, 0, 1) . '.';
    }
}
