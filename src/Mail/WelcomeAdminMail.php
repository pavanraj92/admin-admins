<?php

namespace admin\admins\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
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
        $emailTemplate = \DB::table('emails')->where('slug', 'register_user')->first(['subject', 'description']);

        $subject = $emailTemplate->subject;
        $content = $emailTemplate->description;

        $content = str_replace('%EMAIL_FOOTER%', config('GET.email_footer_text'), $content);
        $subject = str_replace('%APP_NAME%', env('APP_NAME'), $subject);
        $content = str_replace('%APP_NAME%', env('APP_NAME'), $content);
        $content = str_replace('%USER_NAME%', $this?->admin?->full_name, $content);

        $content = str_replace('%EMAIL_ADDRESS%', $this?->admin?->email, $content);
        $content = str_replace('%PASSWORD%', $this?->plainPassword, $content);
        $result = $this->subject($subject)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->replyTo(env('MAIL_FROM_ADDRESS'))
            ->view('admin::admin.email.welcome_admin_mail')
            ->with(['template' => $content]);   
    }
}
