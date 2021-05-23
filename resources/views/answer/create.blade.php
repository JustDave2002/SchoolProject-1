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
                    <form method="POST" action="{{route('answer.store')}}" class="was-validated">
                        @csrf
                        <b><h4>{{$feedbackForm->title}}</h4></b>
                        <br>
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
                        <input type="hidden" id="goBack" name="goBack" value="0">

                        {{--                        {{$index}}{{$counter}}--}}

                        <div class="row">
                            <div class="col-md-6 text-left">
                                @if($index > 0)
                                    <a class="btn btn-danger pull-right disabled " onclick="goBack()">Previous</a>
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

    function goBack(){
        document.getElementById('goBack').value = 1
        document.feedbackForm.submit()
    }
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



