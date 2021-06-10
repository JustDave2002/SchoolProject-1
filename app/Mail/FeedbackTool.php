<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Auth;

class FeedbackTool extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    // This model sends the given email an appropriate email. This function sends the variable "guest" so the blade can decide what link to give 
    public function build(Request $request)
    {
        $guest=request('guest');
        $name=Auth::user()->name;
//        dd($name);
        $public_id=request('public_id');
        return $this->view('emails.mail', compact('public_id','guest', 'name'));
    }
}
