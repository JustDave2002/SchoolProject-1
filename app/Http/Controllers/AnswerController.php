<?php

namespace App\Http\Controllers;

use App\Models\FeedbackForm;
use App\Models\answerForm;
use App\Models\Answer;
use App\Models\formBinder;
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
    public function guestCreate($id)
    {
        dd('12');
        $formBinder = formBinder::find($id);
        $roles = Role::all();

        return view('answer.create',['formBinder' => $formBinder, 'roles' =>$roles]);
    }


    public function guestStore(Request $request)
    {
        $guest = Guest::create([
            'name' => request('name'),
            'role_id' => request('role_id')
        ]);
        $request->session()->put('guest_id', $guest->id);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
//TODO add a function that sends you to the guestAnswerPage, giving you, before you fill in a form, the guest info page
//TODO split guest info form from feedback answer form
        //TODO add the logic from feedbackFormController to this controller to allow multiple pages and previous page button
        $formBinder = formBinder::find($id);

        return view('answer.create',['formBinder' => $formBinder]);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $request->request->add(['user_id' => $user_id]);
            $guest_id = NULL;
        }else{

        }
        $form = answerForm::create([
            'user_id' => request('user_id'),
            'guest_id' => $guest_id,
            'feedback_form_id' => request('ID'),
        ]);
//        $this->validatePoints($request);

        // validate function
        //fix the user table


        $questions = DB::table('Questions')->where('feedback_form_id', '=', $request->ID)->get('id');
        $answers = request('answer');
        //dd($questions, $request, $form, $guest_id);
        //dd($form->id);
        for($i=0; $i<count($questions); $i++) {

            $answer = Answer::create([
                'question_id' => $questions[$i]->id,
                'answer_form_id' => $form->id,
                'answer' => $answers[$i]
            ]);
            //echo $questions[$i]->id.'-'.$answers[$i].'<br>';
        }

        if(Auth::check()){
            return redirect('feedbackForm')->with('message', 'Your feedback has been submitted!');
        }else{
            return redirect('/')->with('message', 'Your feedback has been submitted!');
        }


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
