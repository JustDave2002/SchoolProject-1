<?php

namespace App\Http\Controllers;

use App\Models\FeedbackForm;
use App\Models\Answer;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FeedbackForm $id)
    {
        //dd($id);
        return view('answer.create',['feedbackForm' => $id]);

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
//        $this->validatePoints($request);

        // validate function
        //fix the user table


        $questions = DB::table('Questions')->where('feedback_form_id', '=', $request->ID)->get('id');
        $answers = request('answer');
//        dd($questions);
//        dd($request->all());


        for($i=0; $i<count($questions); $i++) {

            $answer = Answer::create([
                'question_id' => $questions[$i]->id,
                'guest_id' => 1,
                'answer' => $answers[$i]
            ]);
            //echo $questions[$i]->id.'-'.$answers[$i].'<br>';
        }


        return redirect('feedbackForm')->with('message', 'Your feedback has been submitted!');
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
