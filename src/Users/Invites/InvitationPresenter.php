<?php

namespace Thinktomorrow\Chief\Users\Invites;

class InvitationPresenter
{
    /**
     * @var Invitation
     */
    private $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function stateAsLabel(): string
    {
        if ($this->invitation->state() == InvitationState::ACCEPTED) {
            return '';
        }

        $flair = 'label-primary';

        switch ($this->invitation->state()) {
            case InvitationState::REVOKED:
                $flair = 'label-error';
                break;
            case InvitationState::DENIED:
            case InvitationState::EXPIRED:
                $flair = 'label-warning';
                break;
            case InvitationState::PENDING:
            case InvitationState::NONE:
                $flair = 'label-primary';
                break;
        }

        return '<span class="label '.$flair.'">uitnodiging '.$this->invitation->state().'</span>';
    }
}
