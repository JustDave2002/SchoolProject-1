<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\FeedbackTool;
use Illuminate\Support\Facades\Mail;

class FeedbackToolController extends Controller
{
    public function store(Request $request)
    {
        Mail::to($this->validateEmail())->send(new FeedbackTool);
        
        if ($request->email2 !== null) {
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
