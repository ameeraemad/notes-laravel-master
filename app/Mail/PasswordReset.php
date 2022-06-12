<?php

namespace App\Mail;

use App\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $newPassword;

    /**
     * Create a new message instance.
     *
     * @param Student $student
     * @param $newPassword
     */
    public function __construct(Student $student, $newPassword)
    {
        //
        $this->student = $student;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('notes-system@mr-dev.tech', 'Notes System')
            ->subject('Notes System | Student Password Reset')
            ->to($this->student->email)
            ->markdown('mails.student_reset_password');
    }
}
