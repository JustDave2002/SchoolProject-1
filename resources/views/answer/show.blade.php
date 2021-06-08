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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($binder->title) }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(Auth::user()->id == $binder->user_id)

                    {{ $feedbackForms->links() }}
                    <br>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            @foreach($feedbackForms as $feedbackForm)
                                <h3>{{$feedbackForm->title}}</h3>
                            @endforeach
                            <br>
                            <!-- PDF section (everything in here will be in the PDF) -->
                            <div class="canvas_div_pdf">
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
    </div>
</x-app-layout>

@if($formCheck != NULL)
    <!-- Script for making the Chart.js -->
    <script>
        let color = ['rgba(255, 0, 0, 0.4)', 'rgba(0, 0, 255, 0.4)', 'rgba(0, 204, 255, 0.4)', 'rgba(204, 102, 255, 0.4)', 'rgba(128, 0, 128, 0.4)'];
        let counter = 0;
        let myChart = document.getElementById('myChart').getContext('2d');

        const data = {

            labels: [@foreach($feedbackForm->questions as $question)
                '{{$question->question}}',
                @endforeach],
            datasets: [
                @foreach($feedbackForm->answerForms->where('user_id', Auth::user()->id) as $answerForm){
                    label: '{{$answerForm->user->role->name}}',
                    data: [
                        @foreach($answerForm->answers as $answer)
                            '{{$answer->answer}}',
                        @endforeach
                    ],
                    borderColor: '#777',
                    backgroundColor: `${color[counter++]}`,
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

    <!-- Script to make email section visible -->
    <script type="text/javascript">
        function showElement() {
            element = document.querySelector('.formEmail');
            element.style.visibility = 'visible';
        }
    </script>


    <!-- Script for making the PDF download (In development on pdf.blade.php) -->
    {{--<script>--}}
    {{--    function getPDF() {--}}
    {{--        var HTML_Width = $(".canvas_div_pdf").width();--}}
    {{--        var HTML_Height = $(".canvas_div_pdf").height();--}}
    {{--        var top_left_margin = 15;--}}
    {{--        var PDF_Width = HTML_Width + (top_left_margin * 2);--}}
    {{--        var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);--}}
    {{--        var canvas_image_width = HTML_Width;--}}
    {{--        var canvas_image_height = HTML_Height;--}}

    {{--        var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;--}}


    {{--        html2canvas($(".canvas_div_pdf")[0], {allowTaint: true}).then(function (canvas) {--}}
    {{--            canvas.getContext('2d');--}}

    {{--            // console.log(canvas.height+"  "+canvas.width);--}}
    {{--            var imgData = canvas.toDataURL("image/jpeg", 1.0);--}}
    {{--            var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);--}}
    {{--            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);--}}

    {{--            for (var i = 1; i <= totalPDFPages; i++) {--}}
    {{--                pdf.addPage(PDF_Width, PDF_Height);--}}
    {{--                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height * i) + (top_left_margin * 4), canvas_image_width, canvas_image_height);--}}
    {{--            }--}}

    {{--            pdf.save("{{$feedbackForm->title}}.pdf");--}}
    {{--        });--}}
    {{--    };--}}
    {{--</script>--}}

    <!-- Script to make email section visible -->
    <script type="text/javascript">
        function showElement() {
            element = document.querySelector('.formEmail');
            element.style.visibility = 'visible';
        }
    </script>

@endif