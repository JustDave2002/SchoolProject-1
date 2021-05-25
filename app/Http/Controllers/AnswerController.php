<?php

namespace App\Http\Controllers;

use App\Models\FeedbackForm;
use App\Models\answerForm;
use App\Models\Answer;
use App\Models\formBinder;
use App\Models\Question;
use App\Models\Role;
use App\Models\Guest;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;


class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formStart (Request $request, $id)
    {
        //TODO refactor this into a first page, where the counter is set, and if statement decides if user goes to guest page or not
        $formBinder = formBinder::find($id);
        $request->session()->put('counter', $formBinder->form_count);
        $request->session()->put('formBinder', $formBinder);

        list($index, $feedbackForms, $feedbackForm, $counter, $formBinder) = $this->prevPageLogic($request);

        if (Auth::check()){
            return view('answer.create', ['feedbackForm' => $feedbackForm, 'formBinder'=>$formBinder, 'counter' => $counter,'index' => $index]);
        }
        else{
            $roles = Role::all();
            return view('answer.guestCreate',['formBinder' => $formBinder, 'roles' =>$roles]);
        }
    }


    public function guestStore(Request $request)
    {
        $guest = Guest::create([
            'name' => request('name'),
            'role_id' => request('role_id')
        ]);
        $request->session()->put('guest_id', $guest->id);

        return redirect('guestAnswer/create');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
//TODO add a function that sends you to the guestAnswerPage, giving you, before you fill in a form, the guest info page
//TODO split guest info form from feedback answer form
        //TODO add the logic from feedbackFormController to this controller to allow multiple pages and previous page button

        $index = $request->session()->get('formBinder')->form_count - $request->session()->get('counter');
        list($index, $feedbackForms, $feedbackForm, $counter, $formBinder) = $this->prevPageLogic($request);

        return view('answer.create',['formBinder' => $formBinder, 'feedbackForm' => $feedbackForm, 'counter' => $counter,'index' => $index]);

    }


    /**
     * @param Request $request
     * @return array
     */
    public function prevPageLogic(Request $request): array
    {
        $formBinder= $request->session()->get('formBinder');
        $index = $request->session()->get('formBinder')->form_count - $request->session()->get('counter');
        $counter = $request->session()->get('counter');
        $id = $request->session()->get('formBinder')->id;
        $feedbackForms = FeedbackForm::where('form_binder_id', $id)->get();
        $feedbackForm = $feedbackForms->get($index);
        $guestId = $request->session()->get('guest_id');

        $request->session()->put('index', $index);
        $request->session()->put('feedbackForm', $feedbackForm);
        return array($index, $feedbackForms, $feedbackForm, $counter, $formBinder,$guestId);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        list($index, $feedbackForms, $feedbackForm, $counter, $formBinder, $guestId) = $this->prevPageLogic($request);

        if(Auth::check()){
            $user_id = Auth::user()->id;
            $request->request->add(['user_id' => $user_id]);
            $guestId = NULL;
        }else{

        }
        $form = answerForm::create([
            'user_id' => request('user_id'),
            'guest_id' => $guestId,
            'feedback_form_id' => $feedbackForm->id,
        ]);
//        $this->validatePoints($request);

        //TODO validate function



        $questions = Question::where('feedback_form_id', $feedbackForm->id)->get('id');

        $answers = request('answer');
        //dd($questions, $request->all(), $form, $guestId);
        //dd($form->id);
        foreach ($questions as $question){
            $answer = Answer::create([
                'question_id' => $question->id,
                'answer_form_id' => $form->id,
                'answer' => array_shift($answers)
            ]);
        }

        return $this->redirectPage($request);
        if ($counter == 0){
            if(Auth::check()){
                return redirect('feedbackForm')->with('message', 'Your feedback has been submitted!');
            }else{
                return redirect('/')->with('message', 'Your feedback has been submitted!');
            }
        }else{
            $request->session()->decrement('counter');
            return redirect('/answer/create');
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectPage(Request $request)
    {
        if (request('goBack') == 1) {
            //TODO implement goBack function
            $request->session()->increment('counter');
            return redirect('feedbackForm/editForm');
        } else {
            $count = $request->session()->get('counter');
            if ($count == 1) {
                //forgets variables
                $request->session()->forget('counter');
                $request->session()->forget('formBinder');

                //checks where to redirect the guest/user
                if(Auth::check()){
                    return redirect('feedbackForm')->with('message', 'Your feedback has been submitted!');
                }else{
                    return redirect('/')->with('message', 'Your feedback has been submitted!');
                }

            } else {
                $request->session()->decrement('counter');
                return redirect('/answer/create');
            }
        }
    }

    /**
     * edits the form.
     *
     * @return \Illuminate\Http\Response
     */
    public function editForm(Request $request)
    {
        list($index, $feedbackForms, $feedbackForm, $counter) = $this->prevPageLogic($request);

        return view('feedbackForm/editForm',compact('feedbackForm','index', 'counter'));
    }


    /**
     * updates the form.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateForm(Request $request)
    {
        //validates feedbackform
        $request->request->add(['form_binder_id'=> 1]);
        $this->validateFeedbackForm($request);

        //grabs the current feedbackform from DB and updates title
        $feedbackForm =FeedbackForm::findOrFail(request('id'));
        $title = request('title');
        $feedbackForm->update(['title' => $title]);

        //updates questions
        $questionArray= request('question');
        foreach ($feedbackForm->questions as $question){
            $currentQuestion = array_shift($questionArray);
            $question->update(['question' => $currentQuestion]);
        }
        return $this->redirectPage($request);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
