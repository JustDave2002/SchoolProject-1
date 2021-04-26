<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">

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
                            <!-- Js PDF -->
                            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
                            <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
                            <!-- Chart.js -->
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <meta http-equiv="X-UA-Compatible" content="ie=edge">

                            <link rel="stylesheet"
                                  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
                            <title>Feedback</title>
                        </head>
                        <body>
                        <!-- PDF button -->
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" onclick="getPDF()">download PDF</button>
                        <!-- PDF section (everything in here will be in the PDF) -->
                        <div class="canvas_div_pdf">
                            <div class="container">
                                <canvas id="myChart" width="800" height="800"></canvas>
                            </div>
                        </div>
                        <!-- Script for making the Chart.js -->
                        <script>
                            let myChart = document.getElementById('myChart').getContext('2d');

                            const data = {
                                labels: [@foreach($feedbackForm->questions as $question)
                                    '{{$question->question}}',
                                    @endforeach],
                                datasets: [

                                    {
                                        label: 'Teacher',
                                        data: [
                                            @foreach($feedbackForm->questions as $question)
                                                @foreach($question->answers as $answer)
                                                '{{$answer->answer}}',
                                            @endforeach
                                            @endforeach
                                        ],
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
                                    position: 'bottom',
                                    labels: {
                                        fontColor: '#000',
                                        fontSize: 12
                                    }
                                },
                                layout: {
                                    padding: {
                                        left: 50,
                                        right: 400,
                                        bottom: 400,
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

                        <!-- Script for making the PDF download -->
                        <script>
                            function getPDF() {
                                var HTML_Width = $(".canvas_div_pdf").width();
                                var HTML_Height = $(".canvas_div_pdf").height();
                                var top_left_margin = 15;
                                var PDF_Width = HTML_Width + (top_left_margin * 2);
                                var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
                                var canvas_image_width = HTML_Width;
                                var canvas_image_height = HTML_Height;

                                var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;


                                html2canvas($(".canvas_div_pdf")[0], {allowTaint: true}).then(function (canvas) {
                                    canvas.getContext('2d');

                                    // console.log(canvas.height+"  "+canvas.width);

                                    var imgData = canvas.toDataURL("image/jpeg", 1.0);
                                    var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
                                    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);

                                    for (var i = 1; i <= totalPDFPages; i++) {
                                        pdf.addPage(PDF_Width, PDF_Height);
                                        pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height * i) + (top_left_margin * 4), canvas_image_width, canvas_image_height);
                                    }

                                    pdf.save("{{$feedbackForm->title}}.pdf");
                                });
                            };
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

