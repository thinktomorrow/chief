<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Actions;

use Illuminate\Mail\Events\MessageSent;
use Thinktomorrow\Chief\App\Notifications\InvitationMail;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Recording\RichData;

final class RecordSentInvitationMail
{
    public function handle(MessageSent $event): void
    {
        if (($event->data['__laravel_notification'] ?? null) !== InvitationMail::class) {
            return;
        }

        $invitationId = $event->data['chief_audit_invitation_id'] ?? null;

        $html = $event->message->getHtmlBody();
        $subject = $event->message->getSubject();

        History::log(
            type: 'chief.mail.invitation.sent',
            actorType: 'system',
            actorSnapshot: ['name' => 'Chief'],
            category: 'users',
            outcome: 'success',
            summary: 'Invitation email sent',
            context: is_string($invitationId) && $invitationId !== '' ? ['invitation_id' => $invitationId] : [],
            richData: $html === null ? [] : [RichData::mailPreview($html, $subject === 'Uitnodiging tot Chief' ? ['subject' => $subject] : [])],
        );
    }
}
