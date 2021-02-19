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
        if ($this->invitation->stateOf(InvitationState::KEY) == InvitationState::ACCEPTED) {
            return '';
        }

        $flair = 'label-primary';

        switch ($this->invitation->stateOf(InvitationState::KEY)) {
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

        return '<span class="label ' . $flair . '">uitnodiging ' . $this->invitation->stateOf(InvitationState::KEY) . '</span>';
    }
}
