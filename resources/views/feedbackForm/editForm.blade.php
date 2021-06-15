<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit form page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST"
                          action="{{route('feedbackForm.updateForm')}}"
                          class="needs-validation"
                          id="needs-validation"
                          onsubmit="setFormSubmitting()"
                          name="feedbackForm"
                          novalidate
                    >
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title</label><br>
                            <input type="text" id="title" class="form-control" placeholder="Enter Title" name="title"
                                   value="{{$feedbackForm->title}}" required>
                            <div class="valid-feedback"><br></div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        @foreach($feedbackForm->questions as $question)
                            <div class="form-group">
                                <label for="q1">Question</label><br>
                                <input type="text" id="title" class="form-control" placeholder="Question"
                                       name="question[]" value="{{$question->question}}" required>
                                <div class="valid-feedback"><br></div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        @endforeach
                        <br>
                        <br>
                        <input type="hidden" id="goBack" name="goBack" value="0">
                        <input type="hidden" id="id" name="id" value="{{$feedbackForm->id}}">
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    const form = document.getElementById('needs-validation');
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();


    function goBack() {
        if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else{
            document.getElementById('goBack').value = 1
            formSubmitting = true;
            console.log(formSubmitting)
            document.feedbackForm.submit()
        }
        form.classList.add('was-validated');
    }

    //previous page logic
    let formSubmitting = false;
    let setFormSubmitting = function () {
        formSubmitting = true;
    };


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
