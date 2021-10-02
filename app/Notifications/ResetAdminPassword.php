<?php

namespace Thinktomorrow\Chief\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class ResetAdminPassword extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Herstel jouw wachtwoord.')
            ->from(chiefSetting('from_email', null, chiefSetting('contact_email')), chiefSetting('from_name', null, chiefSetting('contact_name')))
            ->view('chief::mails.password-reset', [
                'reset_url' => route('chief.back.password.reset', $this->token),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
