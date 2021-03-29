<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Feedback Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{route('feedbackForm.store')}}" class="was-validated">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label><br>
                            <input type="text" id="title" class="form-control" placeholder="Enter Title" name="title" value="Feedback for Tom" required>
                            <div class="valid-feedback"><br></div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="q1">Question</label><br>--}}
{{--                            <input type="text" id="title" class="form-control" placeholder="Question 1" name="q1" >--}}
{{--                            <div class="valid-feedback"><br></div>--}}
{{--                            <div class="invalid-feedback">Please fill out this field.</div>--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <label for="q1">Question</label><br>
                            <input type="text" id="title" class="form-control" placeholder="Question 1" name="question[]" value="How good is Tom good at teamwork" required>
                            <div class="valid-feedback"><br></div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group">
                            <label for="q2">Question</label><br>
                            <input type="text" id="title" class="form-control" placeholder="Question 2" name="question[]" value="How good is Toms motivation" required>
                            <div class="valid-feedback"><br></div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group">
                            <label for="q3">Question</label><br>
                            <input type="text" id="title" class="form-control" placeholder="Question 3" name="question[]" value="How good is Toms attitude " required>
                            <div class="valid-feedback"><br></div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group">
                            <label for="q4">Question</label><br>
                            <input type="text" id="title" class="form-control" placeholder="Question 4" name="question[]" value="filler question" required>
                            <div class="valid-feedback"><br></div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group">
                            <label for="q5">Question</label><br>
                            <input type="text" id="title" class="form-control" placeholder="Question 5" name="question[]" value="filler question" required>
                            <div class="valid-feedback"><br></div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group">
                            <label for="q6">Question</label><br>
                            <input type="text" id="title" class="form-control" placeholder="Question 6" name="question[]" value="filler question" required>
                            <div class="valid-feedback"><br></div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <br>
                        <br>
                        <input class="btn btn-primary" style="width: 95%" type="submit" value="Submit">
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
