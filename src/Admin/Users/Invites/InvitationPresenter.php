<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites;

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
        if ($this->invitation->getState(InvitationState::KEY) == InvitationState::accepted) {
            return '';
        }

        $flair = 'label-primary';

        switch ($this->invitation->getState(InvitationState::KEY)) {
            case InvitationState::revoked:
                $flair = 'label-error';

                break;
            case InvitationState::denied:
            case InvitationState::expired:
                $flair = 'label-warning';

                break;
            case InvitationState::pending:
            case InvitationState::none:
                $flair = 'label-primary';

                break;
        }

        return '<span class="label ' . $flair . '">uitnodiging ' . $this->invitation->getState(InvitationState::KEY)?->getValueAsString() . '</span>';
    }
}
