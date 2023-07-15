<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected Admin $admin;
    protected  $randomCode;

    /**
     * Create a new message instance.
     */
    public function __construct(Admin $admin , $randomCode)
    {
        //
        $this->admin = $admin;
        $this->randomCode = $randomCode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Reset Password Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'admin_reset_password_email',
            with:['name'=> $this->admin->name , 'randomCode'=>$this->randomCode] ,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
