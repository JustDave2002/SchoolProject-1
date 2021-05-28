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
    <div>
        <x-button class="ml-3" onclick="getPDF()">
            download PDF
        </x-button>
    </div>

{{--    <div class="py-12">--}}
    @foreach($feedbackForms as $feedbackForm)
        <!-- Everything inside this class will be in the PDF -->
            <div style="width: 1200px; height: 1500px; display: inline-block;"
                 class="canvas_div_pdf{{$feedbackForm->id}}">
                @if(Auth::user()->id == $formBinder->user_id)
                    <br>

                    <h1>{{$feedbackForm->title}}</h1>
                    <br>

                    <div class="container">
                        <canvas id="myChart{{$feedbackForm->id}}"
                                style="margin-bottom: 200px; width:1110px; height:740px"></canvas>
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


        @endforeach

{{--    </div>--}}
</x-app-layout>


<script>
    let color = ['rgba(255, 0, 0, 0.4)', 'rgba(0, 0, 255, 0.4)', 'rgba(0, 204, 255, 0.4)', 'rgba(204, 102, 255, 0.4)', 'rgba(128, 0, 128, 0.4)'];

    const options = {


                animation: {
            duration: 0
        },
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
<script >
     window.addEventListener("load",function getPDF() {

        console.log('loaded')
        var HTML_Width = 1200;
        var HTML_Height = 1500;

        var top_left_margin = 15;
        var PDF_Width = HTML_Width + (top_left_margin * 2);
        var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
        var PDF_Width = HTML_Width;
        var PDF_Height = HTML_Height;
        var canvas_image_width = HTML_Width;
        var canvas_image_height = HTML_Height;

        var totalPDFPages = {{$formBinder->form_count}} -1;
        // Math.ceil(HTML_Height / PDF_Height) - 1;

        let pdf = ''
        @foreach($feedbackForms as $feedbackForm)
        html2canvas($(".canvas_div_pdf{{$feedbackForm->id}}")[0], {allowTaint: true,}).then(function (canvas) {
            canvas.getContext('2d');
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            @if ($loop->first)
                pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
            @else
            pdf.addPage(PDF_Width, PDF_Height);
            @endif
            // console.log(canvas.height+"  "+canvas.width);


            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);

            // for (var i = 1; i <= totalPDFPages; i++) {
            //     pdf.addPage(PDF_Width, PDF_Height);
            //     pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height * i) + (top_left_margin * 4), canvas_image_width, canvas_image_height);
            // }

            @if($loop->last)
            pdf.save("{{$formBinder->title}}.pdf");
            @endif

        });
        @endforeach
        //window.history.back();
    });
</script>


{{--<script>--}}
{{--    function getPDF() {--}}
{{--                @foreach($feedbackForms as $feedbackForm)--}}
{{--                var HTML_Width{{$feedbackForm->id->first()}} = $(".canvas_div_pdf{{$feedbackForm->id->first()}}").width();--}}
{{--                var HTML_Height{{$feedbackForm->id->first()}} = $(".canvas_div_pdf{{$feedbackForm->id->first()}}").height();--}}
{{--                @endforeach--}}
{{--        var top_left_margin = 15;--}}
{{--        var PDF_Width = HTML_Width + (top_left_margin * 2);--}}
{{--        var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);--}}
{{--        var canvas_image_width = HTML_Width;--}}
{{--        var canvas_image_height = HTML_Height;--}}

{{--        var totalPDFPages = {{$formBinder->form_count}} -1;--}}
{{--        // Math.ceil(HTML_Height / PDF_Height) - 1;--}}

{{--        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);--}}
{{--        @foreach($feedbackForms as $feedbackForm)--}}
{{--        html2canvas($(".canvas_div_pdf{{$feedbackForm->id}}")[0], {allowTaint: true}).then(function (canvas) {--}}
{{--            canvas.getContext('2d');--}}

{{--            // console.log(canvas.height+"  "+canvas.width);--}}

{{--            var imgData = canvas.toDataURL("image/jpeg", 1.0);--}}
{{--            var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);--}}
{{--            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);--}}

{{--            for (var i = 1; i <= totalPDFPages; i++) {--}}
{{--                pdf.addPage(PDF_Width, PDF_Height);--}}
{{--                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height * i) + (top_left_margin * 4), canvas_image_width, canvas_image_height);--}}
{{--            }--}}


{{--        });--}}
{{--        @endforeach--}}
{{--        pdf.save("{{$formBinder->title}}.pdf");--}}
{{--    };--}}
{{--</script>--}}
