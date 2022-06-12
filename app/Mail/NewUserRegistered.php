<?php

namespace App\Mail;

use App\Student;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param Student $student
     * @param User $user
     */
    public function __construct(Student $student, User $user)
    {
        //
        $this->student = $student;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('notes-system@mr-dev.tech', 'Notes System')
            ->subject('Notes System | App - New User')
            ->to($this->student->email)
            ->markdown('mails.new_user_registered');
    }
}
