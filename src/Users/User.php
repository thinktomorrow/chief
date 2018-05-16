<?php

namespace Chief\Users;

use App\Notifications\ResetAdminPassword;
use Chief\Common\Traits\Enablable;
use Chief\Users\Invites\Invitation;
use Chief\Users\Invites\InvitationState;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, HasMediaTrait, Enablable;

    protected $guard_name = 'admin';

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
        return self::where('email',$email)->first();
    }

    public function invitation()
    {
        return $this->hasOne(Invitation::class, 'invitee_id');
    }

    public function invitationLabel(): string
    {
        if(!$this->invitation) {
            return $this->isEnabled()
                        ? '<span class="label label--primary">actief</span>'
                        : '<span class="label label--error">uitgeschakeld</span><a class="" href="'.route('back.invites.resend', $this->id).'">Stuur nieuwe uitnodiging</a>';
        }

        $flair = 'label--primary';

        switch($this->invitation->state()) {
            case InvitationState::REVOKED:
                $flair = 'label--error';
            break;
            case InvitationState::DENIED:
            case InvitationState::EXPIRED:
                $flair = 'label--warning';
            break;
            case InvitationState::PENDING:
            case InvitationState::NONE:
                $flair = 'label-o--primary';
            break;
        }

        return '<span class="label '.$flair.'">'.$this->invitation->state().'</span>';
    }

    public function roleNames()
    {
        return $this->roles->pluck('name')->toArray();
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

    /**
     * @deprecated: superadmin role is replaced by developer role
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    public function getShortNameAttribute()
    {
      return $this->firstname . ' ' . substr($this->lastname, 0, 1) . '.';
    }
}
