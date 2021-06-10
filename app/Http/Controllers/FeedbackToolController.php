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
        if ($request->email !== NULL && $request->guest1 !== 'on') {
            $request->request->add(['guest' => NULL]);
            Mail::to($this->validateEmail())->send(new FeedbackTool);
        } else if ($request->email !== NULL){
            $request->request->add(['guest' => 'ON']);
            Mail::to($this->validateEmail())->send(new FeedbackTool);
        }
        
        if ($request->email2 !== NULL && $request->guest2 !== 'on') {
            $request->request->add(['guest' => NULL]);
            Mail::to($this->validateEmail2())->send(new FeedbackTool);
        } else if ($request->email2 !== NULL){
            $request->request->add(['guest' => 'ON']);
            Mail::to($this->validateEmail2())->send(new FeedbackTool);
        }

        return redirect(url()->previous());
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
