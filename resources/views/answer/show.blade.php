<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">
        <!-- Js PDF -->
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("My given feedback") }}
        </h1>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div style="margin-top: 10px; margin-bottom: 5px;">
            @if($validated)

                <h2 class="font-semibold  text-gray-800 leading-tight">
                    {{ __($binder->title) }}
                </h2>
                {{ $feedbackForms->links() }}
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        @if(session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        @foreach($feedbackForms as $feedbackForm)
                            <h3>{{$feedbackForm->title}}</h3>
                        @endforeach
                        <br>
                        <!-- Secondary button for editing a form -->
                        <a class="btn pull-right" style="border-color: #3b82f6"
                           href="/answer/{{$binder->public_id}}/edit">Edit Feedback</a>
                        <br>

                        <div class="container">
                            <canvas id="myChart" width="1500" height="1000"></canvas>
                        </div>

                        <!-- table with answer information -->
                        @else
                            You don't have permission to view this Form.
                        @endif
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>

@if($formCheck != NULL && $validated)
    <!-- Script for making the Chart.js -->
    <script>
        let color = ['rgba(255, 0, 0, 0.4)', 'rgba(0, 0, 255, 0.4)', 'rgba(0, 204, 255, 0.4)', 'rgba(204, 102, 255, 0.4)', 'rgba(128, 0, 128, 0.4)'];
        let counter = 0;
        let myChart = document.getElementById('myChart').getContext('2d');

        const data = {
@foreach($feedbackForms as $feedbackForm)
            labels: [@foreach($feedbackForm->questions as $question)
                '{{$question->question}}',
                @endforeach],
            datasets: [
                    @foreach($feedbackForm->answerForms->where('user_id', Auth::user()->id) as $answerForm){
                    label: '{{$answerForm->user->name}}',
                    data: [
                        @foreach ($feedbackForm->questions as $question)
                            '{{$question->answers->where('answer_form_id', $answerForm->id)->first()->answer}}',
                        @endforeach
                    ],
                    borderColor: '#777',
                    backgroundColor: `${color[counter++]}`,
                    borderWidth: 1
                },
                @endforeach
@endforeach
            ]
        };

        // Global Options
        Chart.defaults.global.defaultFontFamily = 'Arial';
        Chart.defaults.global.defaultFontSize = 10;
        Chart.defaults.global.defaultFontColor = 'black';

        const options = {
            scale: {
                ticks: {
                    min: 0,
                    max: 5,
                    stepSize: 1
                }
            },
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    fontColor: '#000',
                    fontSize: 12
                }
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    bottom: 0,
                    top: 0
                },
            }
        };


        let massPopChart = new Chart(myChart, {
            type: 'radar',
            options: options,
            data: data,
        });
    </script>
@endif
