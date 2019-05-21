<?php

namespace Thinktomorrow\Chief\App\Notifications;

use Thinktomorrow\Chief\Users\Invites\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Uitnodiging tot Chief')
            ->from(chiefSetting('contact.email'), chiefSetting('contact.name'))
            ->view('chief::back.mails.invitation', [
                'invitee'    => $this->invitation->invitee,
                'inviter'    => $this->invitation->inviter,
                'accept_url' => $this->invitation->acceptUrl(),
                'deny_url'   => $this->invitation->denyUrl(),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
