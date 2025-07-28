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
        try {
            if (\Schema::hasTable('emails')) {
                $emailTemplate = \DB::table('emails')->where('slug', 'register_admin')->first(['subject', 'description']);
            } else {
                $emailTemplate = null;
            }
        } catch (\Exception $e) {
            $emailTemplate = null;
        }
    

        $subject = $emailTemplate?->subject ?? 'Welcome to ' . env('APP_NAME');
        $content = $emailTemplate?->description ?? '
            <p>Dear %ADMIN_NAME%,</p>

            <p>Thank you for registering with <strong>%APP_NAME%</strong>. Your account has been successfully created, and you now have access to our Quotation Management System.</p>

            <p>Below are your login credentials. Please keep them secure and do not share them with anyone.</p>

            <p><strong>Login Credentials:</strong></p>
            <p>Email Address: %EMAIL_ADDRESS%<br />
            Password: %PASSWORD%</p>

            <p>If you have any questions or require assistance, please contact our support team.</p>

            <p>We wish you a productive experience!</p>

            <p>Best regards,<br />
            The %APP_NAME% Team<br />
            %EMAIL_FOOTER%</p>
        ';

        $content = str_replace('%EMAIL_FOOTER%', config('GET.email_footer_text'), $content);
        $subject = str_replace('%APP_NAME%', env('APP_NAME'), $subject);
        $content = str_replace('%APP_NAME%', env('APP_NAME'), $content);
        $content = str_replace('%ADMIN_NAME%', $this?->admin?->full_name, $content);

        $content = str_replace('%EMAIL_ADDRESS%', $this?->admin?->email, $content);
        $content = str_replace('%PASSWORD%', $this?->plainPassword, $content);
        $result = $this->subject($subject)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->replyTo(env('MAIL_FROM_ADDRESS'))
            ->view('admin::admin.email.master')
            ->with(['template' => $content]);   
    }
}
