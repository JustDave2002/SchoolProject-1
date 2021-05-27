<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\FeedbackTool;
use Illuminate\Support\Facades\Mail;

class FeedbackToolController extends Controller
{
    public function store(Request $request)
    {
//        dd($request);
        Mail::to($request)->send(new FeedbackTool);

        return redirect('/feedbackForm');
    }
}
