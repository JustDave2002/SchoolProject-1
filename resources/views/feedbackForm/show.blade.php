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

                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
                        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
                        <title>Feedback</title>
                    </head>
                    <body>
                    <div class="container">
                        <canvas id="myChart"></canvas>
                    </div>
                    <script>
                            let myChart = document.getElementById('myChart').getContext('2d');

                            const data = {
                                labels: ['Samenwerken', 'Profesioneel gedrag', 'Behulpzaamheid', 'Sociaal', 'Motivatie', 'Doorzettingsvermogen'],
                                datasets: [
                                    {
                                        label: 'Teacher',
                                        data: [1, 3, 4, 5, 3],
                                        borderColor: '#777',
                                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Student',
                                        data: [1, 2, 3, 4, 5],
                                        borderColor: '#777',
                                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Stage Begeleider',
                                        data: [2, 5, 3, 3, 5],
                                        borderColor: '#777',
                                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                        borderWidth: 1
                                    }
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
                                    position: 'right',
                                    labels: {
                                        fontColor: '#000',
                                        fontSize: 12
                                    }
                                },
                                layout: {
                                    padding: {
                                        left: 100,
                                        right: 380,
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

                    </body>
                    </html>

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Questions</th>
                            <th scope="col">First</th>
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
