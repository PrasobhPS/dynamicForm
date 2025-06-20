<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Form;

class FormSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $form;
    public $submission;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Form $form, $submission)
    {
        $this->form = $form;
        $this->submission = $submission;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Form created: ' . $this->form->form_title)
            ->view('emails.form_submission');
    }
}
