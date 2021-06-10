<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\FeedbackTool;
use App\Mail\FeedbackToolGuest;
use Illuminate\Support\Facades\Mail;

class FeedbackToolController extends Controller
{
    public function store(Request $request)
    {
        // This function decides whether the email that is being sent is ment for a guest or for a regular user

        // The first part of the if else is for regular users, it enters this part when the checkbox for guests is not marked

        // The second part of the if else statement is ment for guests, when the statement fails to be a regular user it goes and sends a guest email
        if ($request->email !== NULL && $request->guest1 !== 'on') {
            $request->request->add(['guest' => NULL]);
            Mail::to($this->validateEmail())->send(new FeedbackTool);
        } else if ($request->email !== NULL){
            $request->request->add(['guest' => 'ON']);
            Mail::to($this->validateEmail())->send(new FeedbackTool);
        }
        
        // This function does the same as above 
        if ($request->email2 !== NULL && $request->guest2 !== 'on') {
            $request->request->add(['guest' => NULL]);
            Mail::to($this->validateEmail2())->send(new FeedbackTool);
        } else if ($request->email2 !== NULL){
            $request->request->add(['guest' => 'ON']);
            Mail::to($this->validateEmail2())->send(new FeedbackTool);
        }

        return redirect(url()->previous())->with('message', 'The email has been sent!');
    }

    public function validateEmail():array
    {
        return request()->validate([
            'email' => 'required'
        ]);
    }

    public function validateEmail2():array
    {
        return request()->validate([
            'email2' => 'nullable|email'
        ]);
    }
}
