<?php

namespace App\Http\Controllers;

use App\Models\formBinder;
use App\Models\Question;
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
        $id = Auth::user()->id;
        $feedbackForms = FeedbackForm::where('user_id',$id )
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'asc')
            ->paginate(10);
        //dd($feedbackForms);
        return view('feedbackForm.index', ['feedbackForms' => $feedbackForms]);
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

    public function store(Request $request)
    {
        //is standaard 1
        $user_id = Auth::user()->id;
        $request->request->add(['user_id' => $user_id]);

        $this->validateFormBinder($request);
        $formBinder = formBinder::create([
            'user_id' => request('user_id'),
            'title' => request('title'),
            'form_count' => request('form_count'),
        ]);

        return redirect('feedbackForm/createForm');
    }

    public function createForm()
    {
        return view('feedbackForm/createForm');

    }
    public function storeForm(Request $request)
    {

        $this->validateFeedbackForm($request);

        $form = FeedbackForm::create([
            'user_id' => request('user_id'),
            'title' => request('title'),
        ]);

        foreach(request('question') as $q){
            $question = Question::create([
                'feedback_form_id' => $form->id,
                'question' => $q
            ]);
        }
//        if(){
//
//        }else{
//            return redirect('feedbackForm')->with('message', 'Your feedback form has been made!');
//        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function store(Request $request)
//    {
//        $user_id = Auth::user()->id;
//        $request->request->add(['user_id' => $user_id]);
//        $this->validateFeedbackForm($request);
//
//        $formBinder = formBinder::create([
//            'form_binder_id' => request(user_id),
//            'title' => request('binderTitle'),
//        ]);
//        foreach (request('title')as $formTitle){
//        $form = FeedbackForm::create([
//            'form_binder_id' => $formBinder->id,
//            'title' => $formTitle,
//        ]);
//
//    foreach(request('question') as $q){
//        $question = Question::create([
//          'feedback_form_id' => $form->id,
//          'question' => $q
//        ]);
 //   }
   // }
//        return redirect('feedbackForm')->with('message', 'Your feedback form has been made!');
//    }




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
            'question' => 'required|array|min:6',
            'question.*' => 'required|string',
        ]);
    }

}








