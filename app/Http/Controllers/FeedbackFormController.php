<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeedbackForm;
use Auth;

class FeedbackFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all the posts ordered by published date
        $feedbackForms = FeedbackForm::all();

        return view('feedbackForm.index', compact('feedbackForms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('feedbackForm.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $request->request->add(['user_id' => $user_id]);

        FeedbackForm::create($this->validateFeedbackForm($request));

        return redirect('feedbackForm');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeedbackForm  $feedbackForm
     * @return \Illuminate\Http\Response
     */
    public function show(FeedbackForm $feedbackForm)
    {
        return view('feedbackForm.show', ['feedbackForm' => $feedbackForm]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeedbackForm  $feedbackForm
     * @return \Illuminate\Http\Response
     */
    public function edit(FeedbackForm $feedbackForm)
    {
        return view('feedbackForm.edit', compact('feedbackForm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeedbackForm  $feedbackForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeedbackForm $feedbackForm)
    {
        $feedbackForm->update($this->validateFeedbackForm($request));

        return redirect(route('feedbackForm.index'))->with('status', 'FeedbackForm is bijgewerkt');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeedbackForm  $feedbackForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeedbackForm $feedbackForm)
    {
        //
    }

    private function validateFeedbackForm(Request $request)
    {
        return $request->validate([
            'title' => 'required',
            'user_id' => 'required',
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q4' => 'required',
            'q5' => 'required',
            'q6' => 'required'
        ]);
    }
}
