<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Mail\Events\MessageSent;
use Thinktomorrow\Chief\App\Notifications\InvitationMail;

final class RecordSentInvitationMail
{
    public function handle(MessageSent $event): void
    {
        if (($event->data['__laravel_notification'] ?? null) !== InvitationMail::class) {
            return;
        }

        $html = $event->message->getHtmlBody();
        $subject = $event->message->getSubject();

        History::log(
            type: 'chief.mail.invitation.sent',
            actorType: 'system',
            actorSnapshot: ['name' => 'Chief'],
            category: 'users',
            outcome: 'success',
            summary: 'Invitation email sent',
            richData: $html === null ? [] : [RichData::mailPreview($html, $subject === 'Uitnodiging tot Chief' ? ['subject' => $subject] : [])],
        );
    }
}
