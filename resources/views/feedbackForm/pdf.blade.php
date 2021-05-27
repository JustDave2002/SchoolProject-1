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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"></h2>
    </x-slot>
    <br>
    <x-button class="ml-3" onclick="getPDF()">
        download PDF
    </x-button>
    <!-- Everything inside this class will be in the PDF -->
    <div class="canvas_div_pdf">
        <div class="py-12">
            @foreach($feedbackForms as $feedbackForm)
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @if(Auth::user()->id == $formBinder->user_id)
                        <br>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h1>{{$feedbackForm->title}}</h1>
                                <br>

                                <div class="container">
                                    <canvas id="myChart{{$feedbackForm->id}}" width="1500px" height="1000px"></canvas>
                                </div>

                                <!-- table with answer information -->
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Questions</th>
                                        @foreach($feedbackForm->answerForms as $answerForm)
                                            @if($answerForm->guest == NULL)
                                                <th scope="col">{{$answerForm->user->name}}
                                                    - {{$answerForm->user->role->name}} </th>
                                            @else
                                                <th scope="col">{{$answerForm->guest->name}}
                                                    - {{$answerForm->guest->role->name}} </th>
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
            @endforeach
        </div>
    </div>
</x-app-layout>


<script>
    let color = ['rgba(255, 0, 0, 0.4)', 'rgba(0, 0, 255, 0.4)', 'rgba(0, 204, 255, 0.4)', 'rgba(204, 102, 255, 0.4)', 'rgba(128, 0, 128, 0.4)'];

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

    // Global Options
    Chart.defaults.global.defaultFontFamily = 'Arial';
    Chart.defaults.global.defaultFontSize = 10;
    Chart.defaults.global.defaultFontColor = 'black';

    @foreach($feedbackForms as $feedbackForm)
    console.log({{$feedbackForm->id}});
    {{--    @if($loop->first)--}}
    let counter{{$feedbackForm->id}} = 0;
    let myChart{{$feedbackForm->id}} = document.getElementById(`myChart{{$feedbackForm->id}}`).getContext('2d');

    const data{{$feedbackForm->id}} = {
        labels: [
            @foreach($feedbackForm->questions as $question)
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
                borderColor: '#000',
                backgroundColor: `${color[counter{{$feedbackForm->id}} ++]}`,
                borderWidth: 1
            },
            @endforeach
        ]
    };

    let massPopChart{{$feedbackForm->id}} = new Chart(myChart{{$feedbackForm->id}}, {
        type: 'radar',
        options: options,
        data: data{{$feedbackForm->id}},
    });
    @endforeach

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

        var totalPDFPages = {{$formBinder->form_count}} -1;
        // Math.ceil(HTML_Height / PDF_Height) - 1;

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

            pdf.save("{{$formBinder->title}}.pdf");
        });
    };
</script>



