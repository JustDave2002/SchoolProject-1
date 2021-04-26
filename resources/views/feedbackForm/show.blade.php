<x-app-layout>
<x-slot name="header">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @if(Auth::user()->id == $feedbackForm->user_id)

                <h1>{{$feedbackForm->title}}</h1>
                    <br>
                    <img src="https://www.zohowebstatic.com/sites/default/files/web.png" class="img-fluid" style="height: 600px">

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Questions</th>
{{--                            @foreach($feedbackForm->questions->answers as $answer)--}}
{{--                            <th scope="col">{{$answer->guests->function}}</th>--}}
{{--                            @endforeach--}}
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                        </thead>
                        <tbody>
                    @foreach($feedbackForm->questions as $question)
                        <div></div>
                        <tr>
                            <th scope="row">{{$question->question}}</th>
                            @foreach($question->answers as $answer)
                                <td>{{$answer->answer}}</td>
                            @endforeach
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                    @else
                        You don't have permission to view this Form.
                @endif

            </div>
        </div>
    </div>
</div>
</x-app-layout>
