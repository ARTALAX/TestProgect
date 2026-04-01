<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct() {}

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject(subject: __(key: 'user::emails.welcome_subject'))
            ->view(view: 'user::emails.welcome')
        ;
    }
}
