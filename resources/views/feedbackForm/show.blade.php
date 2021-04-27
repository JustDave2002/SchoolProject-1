<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">
{{--        <meta charset="UTF-8">--}}
{{--        <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--        <meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
{{--        <link rel="stylesheet"--}}
{{--              href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">--}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feedback form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(Auth::user()->id == $feedbackForm->user_id)

                        <h1>{{$feedbackForm->title}}</h1>
                        <br>
                        <div class="container">
                            <canvas id="myChart" width="1500px" height="1000px" style="margin-bottom: 50px"></canvas>
                        </div>
                        <script>
                            let color = ['rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(54, 162, 235, 0.6)'] ;
                            let counter = 0;
                            let myChart = document.getElementById('myChart').getContext('2d');

                            const data = {
                                labels: [@foreach($feedbackForm->questions as $question)
                                    '{{$question->question}}',
                                    @endforeach],
                                datasets: [
                                            @foreach($feedbackForm->answerForms as $answerForm){
                                                @if($answerForm->guest == NULL)
                                                label: '{{$answerForm->user->role->name}}',
                                                @else
                                                label: '{{$answerForm->guest->role->name}}',
                                                @endif
                                                data: [
                                                    @foreach($answerForm->answers as $answer)
                                                        '{{$answer->answer}}',
                                                    @endforeach
                                                ],
                                                borderColor: '#777',
                                                backgroundColor: `${color[counter ++]}`,
                                                borderWidth: 1
                                            },
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

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Questions</th>
                                @foreach($feedbackForm->answerForms as $answerForm)
                                    @if($answerForm->guest == NULL)
                                        <th scope="col">{{$answerForm->user->name}}, {{$answerForm->user->role->name}} </th>
                                    @else
                                        <th scope="col">{{$answerForm->guest->name}}, {{$answerForm->guest->role->name}} </th>
                                    @endif
                                @endforeach
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
