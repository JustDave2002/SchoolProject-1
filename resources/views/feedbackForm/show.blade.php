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
                @if($binder->form_count != $formCount && $formCount != 0)
                    <h3>Uh oh! <br><br></h3>
                    <h4>
                        It looks like something went wrong in the making of this form!
                        If you want to continue making this form, you can edit it here. <br>
                        <a class="btn pull-right" style="border-color: #3b82f6" href="/feedbackForm/{{$binder->public_id}}/edit">Edit</a>


                        <br><br>Alternatively you can delete the form by pressing here.<br>
                        <form method="POST" action="{{route('feedbackForm.destroy', $binder->public_id) }}">@method('DELETE') @csrf
                            <button class="btn pull-right" type="submit" style="border-color: #3b82f6">Delete this form</button></form>
                    </h4>
                @elseif($formCount != 0)

                    {{ $feedbackForms->links() }}
                    <br>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            @foreach($feedbackForms as $feedbackForm)
                                <h3>{{$feedbackForm->title}}</h3>
                            @endforeach
                            <br>
                            <!-- PDF button -->
                            <x-button class="ml-3" onclick="getPDF()">
                                download PDF
                            </x-button>
                            <!-- email implementation -->
                            <x-button class="ml-3" onclick="showElement()">
                                ask feedback
                            </x-button>
                            <!-- Give yourself feedback button -->
                            <x-button class="ml-3" onclick="location.href='/answer/info/{{$binder->public_id}}'">
                                Give yourself feedback
                            </x-button>

                            <!-- Form for E-mail -->
                            <form class="formEmail" name="yes"
                                  style="visibility: hidden; padding-top: 20px; padding-left: 16px"
                                  action="/sendmail/test/">
                                <div class="form-row align-items-center">
                                    <div class="col-auto">
                                        <label class="sr-only" for="inlineFormInput">E-mail</label>
                                        <input type="text" class="form-control mb-2" name="email"
                                               placeholder="Enter a email">
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="autoSizingCheck"
                                                   name="guest">
                                            <label class="form-check-label" for="autoSizingCheck">
                                                Guest
                                            </label>
                                        </div>
                                    </div>
                                    <input style="display:none;" value="{{$binder->public_id}}" name="public_id">
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary mb-2">Send</button>
                                    </div>
                                </div>
                            </form>

                            <!-- PDF section (everything in here will be in the PDF) -->
                            <div class="canvas_div_pdf">
                                <div class="container">
                                    <canvas id="myChart" width="1500" height="1000"></canvas>
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
                                <a class="btn pull-right" style="border-color: #3b82f6" href="/feedbackForm/{{$binder->public_id}}/edit">Edit</a>

                                <form method="POST" action="{{route('feedbackForm.destroy', $binder->public_id) }}">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn pull-right" type="submit" onclick="return confirm('Are you sure you want to delete this form?')" style="border-color: #3b82f6">Delete this form</button>
                                </form>

                                @else
                                    <div class="danger">
                                        <h3>Uh oh! <br><br></h3>
                                        <h4>
                                            It looks like something went wrong in the making of this form!
                                            It is best to delete this form and try again. If the problem persists, we
                                            fucked up.<br>
                                        </h4>

                                        <form method="POST" action="{{route('feedbackForm.destroy', $binder->public_id) }}">@method('DELETE') @csrf
                                            <button class="btn pull-right" type="submit" style="border-color: #3b82f6">Delete this form</button></form>
                                    </div>
                                @endif
                                @else
                                    You don't have permission to view this Form.
                                @endif
                            </div>
                        </div>
                    </div>
        </div>
    </div>

@foreach($feedbackFormsPDF as $form)
    <!-- Everything inside this class will be in the PDF -->
        <div style="width: 1200px; height: 1500px;  position: absolute;
  left:     -10000px; display: inline-block;"
             class="canvas_div_pdf{{$form->id}}" id="clipped">
            @if(Auth::user()->id == $binder->user_id)
                <br>

                <h1>{{$form->title}}</h1>
                <br>

                <div class="container">
                    <canvas id="myChart{{$form->id}}"
                            style="margin-bottom: 200px; width:1110px; height:740px"></canvas>
                </div>

                <!-- table with answer information -->
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Questions</th>
                        @foreach($form->answerForms as $answerForm)
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
                    @foreach($form->questions as $question)
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
</x-app-layout>

@if($binder->form_count == $formCount)
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


    <!-- Script to make email section visible -->
    <script type="text/javascript">
        function showElement() {
            element = document.querySelector('.formEmail');
            element.style.visibility = 'visible';
        }
    </script>

    <!-- Script to make email section visible -->
    <script type="text/javascript">
        function showElement() {
            element = document.querySelector('.formEmail');
            element.style.visibility = 'visible';
        }
    </script>


    <script>
        //let color = ['rgba(255, 0, 0, 0.4)', 'rgba(0, 0, 255, 0.4)', 'rgba(0, 204, 255, 0.4)', 'rgba(204, 102, 255, 0.4)', 'rgba(128, 0, 128, 0.4)'];

        const optionsPDF = {


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

        @foreach($feedbackFormsPDF as $feedbackForm)
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
            options: optionsPDF,
            data: data{{$feedbackForm->id}},
        });
        @endforeach

    </script>

    <!-- Script for making the PDF download -->
    <script >
        function getPDF() {

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

            var totalPDFPages = {{$binder->form_count}} -1;
            // Math.ceil(HTML_Height / PDF_Height) - 1;

            let pdf = ''
            @foreach($feedbackFormsPDF as $feedbackForm)
            html2canvas($(".canvas_div_pdf{{$feedbackForm->id}}")[0], {allowTaint: true, scale: 2}).then(function (canvas) {
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
                pdf.save("{{$binder->title}}.pdf");
                @endif

            });
            @endforeach
        };
    </script>


    <style>
        #clipped {
            clip-path: inset(0 100% 0 0);
        }

    </style>
@endif

