<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($formBinder->title) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{route('answer.save')}}" class="was-validated"
                          onsubmit="setFormSubmitting()" name="answerForm">
                        @csrf
                        <b><h4>{{$feedbackForm->title}}</h4></b>
                        <br>
                        @foreach($feedbackForm->questions as $question)
                            <div></div>
                            <div class="form-group" style="width: 89%; display: inline-block">
                                <label for="q1">{{$question->question}}</label><br>
                                <input type="range" id="answer" class="answer" list="num" placeholder="Question"
                                       name="answer[]" value="3" min="1" max="5">
                                <datalist id="num">
                                    <option value="1" label="--">
                                    <option value="2" label="-">
                                    <option value="3" label="o">
                                    <option value="4" label="+">
                                    <option value="5" label="++">
                                </datalist>
                            </div>
                            <div style="display: inline-block; vertical-align:top">
                                <a class="btn btn-primary pull-right" onclick="showComment({{$loop->index}})" style="margin-top: 25px">Comment</a>
                            </div>
                            <div class="form-group" id="field{{$loop->index}}" style="display: none">
                                <label style="margin: 0px" for="title">Extra feedback</label><br>
                                <input type="text" id="comment" class="form-control" placeholder="Type some extra feedback or clarification here" name="comment[]"maxlength="200">
                                <div class="valid-feedback"><br></div>
                            </div>


                            <br>
                        @endforeach
                        <br>
                        <br>
                        <input type="hidden" id="goBack" name="goBack" value="0">
                        {{--                        {{$index}}{{$counter}}--}}
                        <div class="row">
                            <div class="col-md-6 text-left">
                                @if($index > 0)
                                    <a class="btn pull-right" style="border-color: #3b82f6"
                                       onclick="goBack()">Previous</a>
                                @endif
                            </div>
                            @if($counter == 1)
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            @else
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let shown = 0

    function showComment(index) {
        let commentField = document.getElementById(`field${index}`)
        if (shown === 0){
            commentField.style.display = 'block';
            shown = 1
        } else {
            commentField.style.display = 'none';
            shown = 0
        }
    }

    let formSubmitting = false;
    let setFormSubmitting = function () {
        formSubmitting = true;
    };


    function goBack() {
        document.getElementById('goBack').value = 1
        formSubmitting = true;
        console.log(formSubmitting)
        document.answerForm.submit()
    }


    window.onload = function () {
        window.addEventListener("beforeunload", function (e) {
            console.log(formSubmitting)
            if (formSubmitting == true) {
                return undefined;
            }

            const confirmationMessage = 'It looks like you have been editing something. '
                + 'If you leave before saving, your changes will be lost.';

            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
        });
    };
</script>
<style>
    datalist {
        width: 123.5%;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
    }

    datalist option {
        -webkit-box-flex: 1;
        -webkit-flex-grow: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        -webkit-flex-basis: 0;
        -ms-flex-preferred-size: 0;
        flex-basis: 0;
        font-weight: bold;
    }

    .answer {
        width: 100%;
    }
</style>



