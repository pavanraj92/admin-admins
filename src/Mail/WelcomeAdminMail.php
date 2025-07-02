<?php

namespace admin\admins\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $plainPassword;

    public function __construct($admin, $plainPassword)
    {
        $this->admin = $admin;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Welcome to ' . env('APP_NAME'))
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->view('admin::admin.email.welcome_admin_mail')
            ->with([
                'fullName' => $this?->admin?->full_name,
                'email' => $this?->admin?->email,
                'password' => $this?->plainPassword,
            ]);
    }
}
