<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\FormSubmissionMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Form;

class NotifyFormSubmitted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $form;
    public $submission;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Form $form, array $submission)
    {
        $this->form = $form;
        $this->submission = $submission;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Mail::to('admin@example.com') // ← Change this to your desired recipient
        //     ->send(new FormSubmissionMail($this->form, $this->submission));
        \Log::info("Form '{$this->form->form_title}' has been submitted successfully.");
    }
}
