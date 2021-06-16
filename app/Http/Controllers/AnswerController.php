<?php

namespace App\Http\Controllers;

use App\Models\FeedbackForm;
use App\Models\AnswerForm;
use App\Models\Answer;
use App\Models\FormBinder;
use App\Models\Question;
use App\Models\Role;
use App\Models\Guest;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;


class AnswerController extends Controller
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

        $answerForms = AnswerForm::where('user_id', $id)->get();

        $formBinderIds = [];
        $binderTest = collect([]);

        //requests all forms filled in by a specific user
        foreach ($answerForms as $answerForm) {
            $feedbackForm = FeedbackForm::where('id', $answerForm->feedback_form_id)->first();

            $formBinderId = $feedbackForm->form_binder_id;
            $currentBinder = FormBinder::where('id', $formBinderId)->first();

            //checks if binder already exists
            if ($binderTest->contains($currentBinder) === false) {
                $binderTest->push($currentBinder);
                array_push($formBinderIds, $feedbackForm->form_binder_id);
            }
        }
       // dd($formBinderIds);
//        $binderTest = $binderTest->sortDesc();


        $formBinders = FormBinder::whereIn('id', $formBinderIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('answer.index', ['formBinders' => $formBinders]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formStart(Request $request, $public_id)
    {
        $request->session()->forget('answerForms');
        $formBinder = FormBinder::where('public_id', $public_id)->first();

        //sets item in session
        $request->session()->put('counter', $formBinder->form_count);
        $request->session()->put('formBinder', $formBinder);

        //list of useful variables
        list($index, $feedbackForms, $feedbackForm, $counter, $formBinder) = $this->prevPageLogic($request);

        //checks if a user has already answered this feedback form and redirects with an error
        if (Auth::check() && AnswerForm::where('feedback_form_id', $feedbackForm->id)->where('user_id', Auth::user()->id)->exists()) {

            return redirect('/answer/' . $public_id)->with('error', 'You have already filled in this form');
        } else {
            //if a user is logged in go to first form page
            if (Auth::check()) {
                return view('answer.create', ['feedbackForm' => $feedbackForm, 'formBinder' => $formBinder, 'counter' => $counter, 'index' => $index]);
            } //if user is a guest, go to page to get name and function
            else {
                $roles = Role::all();
                return view('answer.guestCreate', ['formBinder' => $formBinder, 'roles' => $roles]);
            }
        }


    }


    public function guestStore(Request $request)
    {
        $this->validateGuestName($request);

        //creates the guest entry
        $guest = Guest::create([
            'name' => request('name'),
            'role_id' => request('role_id')
        ]);

        //stores guest in session
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
        list($index, $feedbackForms, $feedbackForm, $counter, $formBinder, $guestId, $answerForms) = $this->prevPageLogic($request);

        //checks if answerform exists
        $formTest = $answerForms[$index] ?? NULL;
        if ($formTest == NULL) {
            return view('answer.create', compact('formBinder', 'feedbackForm', 'counter', 'index'));
        } //go to the edit form page
        else {
            $answerForm = $answerForms[$index];
            return view('answer/edit', compact('feedbackForm', 'formBinder', 'answerForm', 'index', 'counter'));
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate answers
        $this->validateAnswers($request);

        list($index, $feedbackForms, $feedbackForm, $counter, $formBinder, $guestId) = $this->prevPageLogic($request);

        //if user is logged in, set user id in session and guest id null
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $request->request->add(['user_id' => $user_id]);
            $guestId = NULL;
        } else {
        }

        //create answerForm
        $form = AnswerForm::create([
            'user_id' => request('user_id'),
            'guest_id' => $guestId,
            'feedback_form_id' => $feedbackForm->id,
        ]);

        //set answerForm in session
        $request->session()->push('answerForms', $form);

        //get arrays of answers, questions and comments
        $questions = Question::where('feedback_form_id', $feedbackForm->id)->get('id');
        $answers = request('answer');
        $comments = request('comment');

        //save each question individually
        foreach ($questions as $question) {
            $answer = Answer::create([
                'question_id' => $question->id,
                'answer_form_id' => $form->id,
                'answer' => array_shift($answers),
                'comment' => array_shift($comments)
            ]);
        }

        return $this->redirectPage($request);
    }

    /**
     * Function which has all redirects, and based on some variables, redirects the user to the right next step
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectPage(Request $request)
    {
        //if user requested previous page
        if (request('goBack') == 1) {
            $request->session()->increment('counter');
            return redirect('/answer/edit');
        } else {
            //if its the last page
            $count = $request->session()->get('counter');
            if ($count == 1) {
                //forgets variables
                //TODO forget all variables
                $request->session()->forget('counter');
                $request->session()->forget('formBinder');
                $request->session()->forget('');

                //checks where to redirect the guest/user
                if (Auth::check()) {
                    return redirect('feedbackForm')->with('message', 'Your feedback has been submitted!');
                } else {
                    return redirect('/')->with('message', 'Your feedback has been submitted!');
                }
                //goes to the next page in form
            } else {
                $request->session()->decrement('counter');
                if (Auth::check()) {
                    return redirect('/answer/create');
                } else {
                    return redirect('guestAnswer/create');
                }
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
        //validate answers
        $this->validateAnswers($request);

        list($index, $feedbackForms, $feedbackForm, $counter, $formBinder, $guestId, $answerForms) = $this->prevPageLogic($request);

        //dd($answerForms);
        $answerForm = $answerForms[$index];

        return view('answer/edit', compact('feedbackForm', 'formBinder', 'answerForm', 'index', 'counter'));
    }


    /**
     * updates the form.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateForm(Request $request)
    {
        //validate answers
        $this->validateAnswers($request);

        list($index, $feedbackForms, $feedbackForm, $counter, $formBinder, $guestId, $answerForms) = $this->prevPageLogic($request);

        //gets the current answerform
        $answerForm = $answerForms[$index];

        //gets all answers and comments
        $answers = request('answer');
        $comments = request('comment');

        //updates all answers
        foreach ($answerForm->answers as $answer) {
            $currentAnswer = array_shift($answers);
            $currentComment = array_shift($comments);
            $answer->update(['answer' => $currentAnswer, 'comment' => $currentComment]);
        }

        return $this->redirectPage($request);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($public_id)
    {
        $binder = FormBinder::where('public_id', $public_id)->first();
        $id = $binder->id;
        $formCheck = FeedbackForm::where('form_binder_id', $id)->first();
        $feedbackForms = FeedbackForm::where('form_binder_id', $id)
            ->orderBy('created_at', 'asc')
            ->paginate(1);

        $userIds = [];
        foreach ($feedbackForms as $feedbackForm) {
            foreach ($feedbackForm->answerForms as $answerForm) {
                array_push($userIds, $answerForm->user_id);
            }
        }
        $validated = in_array(Auth::user()->id, $userIds);
//        dd($feedbackForms);
        return view('answer.show', compact('formCheck', 'binder', 'feedbackForms', 'validated'));
    }


    /**
     * Sets the variables to be able to hook into the editForm function
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $publicId)
    {
        $formBinder = FormBinder::where('public_id', $publicId)->first();
        $feedbackForms = FeedbackForm::where('form_binder_id', $formBinder->id)->get();
        $answerForms = collect([]);
        foreach ($feedbackForms as $feedbackForm) {
            $answerForm = AnswerForm::where('feedback_form_id', $feedbackForm->id)->where('user_id', Auth::user()->id)->first();
            $answerForms->push($answerForm);
        }

        $request->session()->put('formBinder', $formBinder);
        $request->session()->put('answerForms', $answerForms);
        $request->session()->put('counter', $formBinder->form_count);
        return redirect('answer/create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * @param Request $request
     * @return array
     */
    public function prevPageLogic(Request $request): array
    {
        $formBinder = $request->session()->get('formBinder');
        $index = $request->session()->get('formBinder')->form_count - $request->session()->get('counter');
        $counter = $request->session()->get('counter');
        $id = $request->session()->get('formBinder')->id;
        $feedbackForms = FeedbackForm::where('form_binder_id', $id)->get();
        $feedbackForm = $feedbackForms->get($index);
        $guestId = $request->session()->get('guest_id');
        $answerForms = $request->session()->get('answerForms');
//        dd($answerForms);

        $request->session()->put('index', $index);
        $request->session()->put('feedbackForm', $feedbackForm);
        return array($index, $feedbackForms, $feedbackForm, $counter, $formBinder, $guestId, $answerForms);
    }

    private function validateAnswers(Request $request)
    {
        return $request->validate([
            'comment' => 'required|array|max:6',
            'comment.*' => 'string|nullable|max:200',
            'answer' => 'required|array|min:6',
            'answer.*' => 'required|numeric'
        ]);
    }

    private function validateGuestName(Request $request)
    {
        return $request->validate([
            'name' => 'required|string'
        ]);
    }

}
