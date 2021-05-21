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
        $formBinders = FormBinder::where('user_id',$id )
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'asc')
            ->paginate(10);
        //dd($feedbackForms);
        return view('feedbackForm.index', ['formBinders' => $formBinders]);
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
        $user_id = Auth::user()->id;
        $request->request->add(['user_id' => $user_id]);


        $this->validateFormBinder($request);
        $formBinder = formBinder::create([
            'user_id' => request('user_id'),
            'title' => request('title'),
            'form_count' => request('form_count'),
        ]);
        $request->session()->forget('formCount');
        $request->session()->forget('formBinder');
        $request->session()->put('formBinder', $formBinder);
        $request->session()->put('formCount', request('form_count'));

        return redirect('feedbackForm/createForm');
    }

    public function createForm(Request $request)
    {
        $formCount = $request->session()->get('formCount');
        $formBinder = $request->session()->get('formBinder');
        return view('feedbackForm/createForm', compact('formBinder', 'formCount'));

    }


    public function storeForm(Request $request)
    {
        $formBinder = $request->session()->get('formBinder');
        $request->request->add(['form_binder_id' => $formBinder->id]);

        $this->validateFeedbackForm($request);

        $form = FeedbackForm::create([
            'form_binder_id' => request('form_binder_id'),
            'title' => request('title'),
        ]);
        foreach(request('question') as $q){
            $question = Question::create([
                'feedback_form_id' => $form->id,
                'question' => $q
            ]);
        }
        $count = $request->session()->get('formCount');
        if($count == 1){
            return redirect('feedbackForm')->with('message', 'Your feedback form has been made!');

        }
        else{
            $request->session()->decrement('formCount');
            return redirect('feedbackForm/createForm');
        }
    }





//    $request->session()->increment('count');
//$value = $request->session()->pull('key', 'default');
//$request->session()->push('user.teams', 'developers');



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\formBinder  $formBinder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $formBinder = formBinder::find($id);
        $feedbackForms = FeedbackForm::where('form_binder_id',$id)
            ->orderBy('created_at', 'asc')
            ->paginate(1);
        return view('feedbackForm.show', ['binder' => $formBinder, 'feedbackForms' =>$feedbackForms]);
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
            'title' => 'required|string',
            'form_binder_id' => 'required|numeric',
            'question' => 'required|array|min:6',
            'question.*' => 'required|string',
        ]);
    }

    private function validateFormBinder(Request $request)
    {
        return $request->validate([
            'user_id' => 'required|numeric',
            'title' => 'required|string',
            'form_count' => 'required|numeric'
        ]);
    }

}








