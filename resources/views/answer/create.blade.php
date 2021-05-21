<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fill in the feedback') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{route('answer.store')}}" class="was-validated">
                        @csrf
                        <input type="hidden" id="ID" name="ID" value="{{$feedbackForm->id}}">

                        @if(Auth::check() == 0)
                            <div class="form-group">
                                <label for="name">Name</label><br>
                                <input type="text" id="name" class="form-control" placeholder="Enter your name"
                                       name="name" required>
                                <div class="valid-feedback"><br></div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form-group">
                                <label for="title">Function</label><br>

                                <select id="role_id" class="block mt-1 w-full" style="margin-bottom: 40px"
                                        name="role_id" required/>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                    </select>
                            </div>
                        @endif
                        @foreach($feedbackForm->questions as $question)
                            <div></div>
                            <div class="form-group">
                                <label for="q1">{{$question->question}}</label><br>
                                <input type="range" id="answer" class="answer" list="num" placeholder="Question 1"
                                       name="answer[]" value="4" min="1" max="5">
                                <datalist id="num">
                                    <option value="1" label="--">
                                    <option value="2" label="-">
                                    <option value="3" label="o">
                                    <option value="4" label="+">
                                    <option value="5" label="++">
                                </datalist>
                            </div>
                        @endforeach
                        <br>
                        <br>
                        <input class="btn btn-primary" style="width: 95%" type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

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



