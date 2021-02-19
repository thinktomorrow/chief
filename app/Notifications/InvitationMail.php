<?php

namespace Thinktomorrow\Chief\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;

class InvitationMail extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

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
            ->from(chiefSetting('contact_email'), chiefSetting('contact_name'))
            ->view('chief::back.mails.invitation', [
                'invitee' => $this->invitation->invitee,
                'inviter' => $this->invitation->inviter,
                'accept_url' => $this->invitation->acceptUrl(),
                'deny_url' => $this->invitation->denyUrl(),
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
