<?php

namespace Modules\User\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\User\Emails\WelcomeMail;
use Modules\User\Events\UserRegistered;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user->email)
            ->send(mailable: new WelcomeMail())
        ;
    }
}
