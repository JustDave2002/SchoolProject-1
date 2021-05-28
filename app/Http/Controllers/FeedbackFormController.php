<?php

namespace App\Http\Controllers;

use App\Models\formBinder;
use App\Models\Question;
use Database\Seeders\FormBinderSeeder;
use Illuminate\Http\Request;
use App\Models\FeedbackForm;
use Auth;
use function PHPUnit\Framework\isEmpty;
use function Sodium\add;
use Ramsey\Uuid\Uuid;

class FeedbackFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all the posts ordered by published date and paginated
        $id = Auth::user()->id;
        $formBinders = FormBinder::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'asc')
            ->paginate(10);
        return view('feedbackForm.index', ['formBinders' => $formBinders]);
    }


    /**
     * Show the form for creating a new formBinder.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('feedbackForm.create');
    }

    /**
     * stores the formBinder.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //request logged in user for later use
        $user_id = Auth::user()->id;
        $request->request->add(['user_id' => $user_id]);

        //validate request
        $this->validateFormBinder($request);

        //make formBinder
        $formBinder = formBinder::create([
            'public_id' => Uuid::uuid4(),
            'user_id' => request('user_id'),
            'title' => request('title'),
            'form_count' => request('form_count'),
        ]);

        //add formBinder and initialise counter to session
        $request->session()->put('formBinder', $formBinder);
        $request->session()->put('counter', request('form_count'));

        return redirect('feedbackForm/createForm');
    }

    /**
     * Show the form for creating a new form.
     *
     * @return \Illuminate\Http\Response
     */
    public function createForm(Request $request)
    {
        //gathers some needed data
        list($index, $feedbackForms, $feedbackForm, $counter) = $this->prevPageLogic($request);

        //if the formPage does not exist yet create one
        if ($feedbackForms->get($index) == NULL) {
            //gets variables from session and returns them in the view
            $formBinder = $request->session()->get('formBinder');
            return view('feedbackForm/createForm', compact('formBinder', 'counter', 'index'));
        } //go to the edit form page
        else {
            return view('feedbackForm/editForm', compact('feedbackForm', 'counter', 'index'));
        }
    }

    /**
     * stores the form.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeForm(Request $request)
    {
        //gets formBinder id from session and stores it in the request for later
        $formBinder = $request->session()->get('formBinder');
        $request->request->add(['form_binder_id' => $formBinder->id]);

        //validates  feedbackForm
        $this->validateFeedbackForm($request);

        //creates feedbackForm
        $form = FeedbackForm::create([
            'form_binder_id' => request('form_binder_id'),
            'title' => request('title'),
        ]);

        //if there is no collection of feedback forms, hide the form binder title by
        //making it the same as the feedback form title
        if ($formBinder->form_count == 1) {
            $formBinder->update(['title' => request('title')]);
        }

        //creates questions
        foreach (request('question') as $q) {
            $question = Question::create([
                'feedback_form_id' => $form->id,
                'question' => $q
            ]);
        }
        return $this->redirectPage($request);
    }


    /**
     * edits the form.
     *
     * @return \Illuminate\Http\Response
     */
    public function editForm(Request $request)
    {
        list($index, $feedbackForms, $feedbackForm, $counter) = $this->prevPageLogic($request);

        return view('feedbackForm/editForm', compact('feedbackForm', 'index', 'counter'));
    }


    /**
     * updates the form.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateForm(Request $request)
    {
        //validates feedbackform
        $request->request->add(['form_binder_id' => 1]);
        $this->validateFeedbackForm($request);

        //grabs the current feedbackform from DB and updates title
        $feedbackForm = FeedbackForm::findOrFail(request('id'));
        $title = request('title');
        $feedbackForm->update(['title' => $title]);

        //updates questions
        $questionArray = request('question');
        foreach ($feedbackForm->questions as $question) {
            $currentQuestion = array_shift($questionArray);
            $question->update(['question' => $currentQuestion]);
        }
        return $this->redirectPage($request);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param \App\Models\formBinder $formBinder
     * @return \Illuminate\Http\Response
     */
    public function show($public_id)
    {
        $formBinder = formBinder::where('public_id', $public_id)->first();
        $id = $formBinder->id;
        $formCheck = FeedbackForm::where('form_binder_id', $id)->first();
        $feedbackForms = FeedbackForm::where('form_binder_id', $id)
            ->orderBy('created_at', 'asc')
            ->paginate(1);
        return view('feedbackForm.show', ['formCheck'=> $formCheck, 'binder' => $formBinder, 'feedbackForms' => $feedbackForms]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\FeedbackForm $feedbackForm
     * @return \Illuminate\Http\Response
     */
    public function edit(FeedbackForm $feedbackForm)
    {
        return view('feedbackForm.edit', compact('feedbackForm'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\FeedbackForm $feedbackForm
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
     * @param \App\Models\formBinder $formBinder
     * @return \Illuminate\Http\Response
     */
    public function destroy($publicId)
    {
        $formBinder=formBinder::where('public_id', $publicId)->first();
        if (isEmpty(FeedbackForm::where('form_binder_id', $formBinder->id)->first())){
        } else{
            $feedbackForms = FeedbackForm::select('form_binder_id', $formBinder->id)->get();
            foreach ($feedbackForms as $feedbackForm) {
                $feedbackForm->delete();
            }
        }
        $formBinder->delete();
        return redirect(route('feedbackForm.index'))->with('error', 'Your feedback form has been deleted!');

    }

    public function makePDF($id){
   $formBinder=formBinder::where('public_id', $id)->first();
   $feedbackForms = FeedbackForm::where('form_binder_id', $formBinder->id)->get();
        return view('feedbackForm/pdf', compact('formBinder', 'feedbackForms'));
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


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectPage(Request $request)
    {
        if (request('goBack') == 1) {
            $request->session()->increment('counter');
            return redirect('feedbackForm/editForm');
        } else {
            $count = $request->session()->get('counter');
            if ($count == 1) {
                $request->session()->forget('counter');
                $request->session()->forget('formBinder');
                return redirect('feedbackForm')->with('message', 'Your feedback form has been made!');

            } else {
                $request->session()->decrement('counter');
                return redirect('feedbackForm/createForm');
            }
        }
    }


    /**
     * @param Request $request
     * @return array
     */
    public function prevPageLogic(Request $request): array
    {
        $index = $request->session()->get('formBinder')->form_count - $request->session()->get('counter');
        $counter = $request->session()->get('counter');
        $id = $request->session()->get('formBinder')->id;
        $feedbackForms = FeedbackForm::where('form_binder_id', $id)->get();
        $feedbackForm = $feedbackForms->get($index);

        $request->session()->put('index', $index);
        $request->session()->put('feedbackForm', $feedbackForm);
        return array($index, $feedbackForms, $feedbackForm, $counter);
    }
}








