<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Emailtool;
use Illuminate\Support\Facades\Mail;

class EmailtoolController extends Controller
{
    public function store(Request $request)
    {
//        dd($request);
        Mail::to($request)->send(new Emailtool);

        return redirect('/feedbackForm');
    }
}
