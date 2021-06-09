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
            <!-- Alert for incomplete form -->
            @if(Auth::user()->id == $binder->user_id)
                @if($binder->form_count != $formCount && $formCount != 0)
                    <h3>Uh oh! <br><br></h3>
                    <h4>
                        It looks like something went wrong in the making of this form!
                        If you want to continue making this form, you can edit it here. <br>
                        <a class="btn pull-right" style="border-color: #3b82f6"
                           href="/feedbackForm/{{$binder->public_id}}/edit">Edit</a>
                        <br><br>Alternatively you can delete the form by pressing here.<br>
                        <form method="POST"
                              action="{{route('feedbackForm.destroy', $binder->public_id) }}">@method('DELETE') @csrf
                            <button class="btn pull-right" type="submit" style="border-color: #3b82f6">Delete this
                                form
                            </button>
                        </form>
                    </h4>
                @elseif($formCount != 0)
                <!-- Pagination -->
                    {{ $feedbackForms->links() }}
                    <br>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            @foreach($feedbackForms as $feedbackForm)
                                <div class="row">
                                    <div class="col-md-6 text-left"><h3>{{$feedbackForm->title}}</h3></div>
                                    <div class="col-md-6 text-right">{{ $binder->created_at->format('m/d/Y')}}</div>
                                </div>
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
                            <form class="formEmail was-validated" name="yes"
                                  style="visibility: hidden; padding-top: 20px; padding-left: 16px"
                                  action="/sendmail/test/">
                                <div class="form-row align-items-center">
                                    <div class="col-auto">
                                        <label class="sr-only" for="inlineFormInput">E-mail</label>
                                        <input type="email" class="form-control mb-2" name="email"
                                               placeholder="Enter a email" required>
                                        <div class="invalid-feedback">Email did not meet requirements</div>
                                    </div>
                                    <div class="col-auto">
                                        <label class="sr-only" for="inlineFormInput">E-mail</label>
                                        <input type="email" class="form-control mb-2" name="email2"
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

                            <div class="container">
                                <canvas id="myChart" width="1500" height="1000"></canvas>
                            </div>

                            <!-- table with answer information -->
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Questions</th>
                                    @foreach($feedbackForm->answerForms as $answerForm)
                                        @if($loop->first)
                                            <th scope="col">Average</th>
                                            @if($answerForm->guest == NULL)
                                                <th scope="col">{{$answerForm->user->name}}
                                                    - <br>{{$answerForm->user->role->name}}
                                                    @if($answerForm->user->role_verified)
                                                        <div class="verified"></div>
                                                    @else
                                                        <div class="not_verified"> x</div>
                                                    @endif
                                                </th>
                                            @else
                                                <th scope="col">{{$answerForm->guest->name}}
                                                    - {{$answerForm->guest->role->name}} </th>
                                            @endif
                                        @else
                                            @if($answerForm->guest == NULL)
                                                <th scope="col">{{$answerForm->user->name}}
                                                    - <br>{{$answerForm->user->role->name}}
                                                    @if($answerForm->user->role_verified)
                                                        <div class="verified"></div>
                                                    @else
                                                        <div class="not_verified"> x</div>
                                                    @endif
                                                </th>
                                            @else
                                                <th scope="col">{{$answerForm->guest->name}}
                                                    - {{$answerForm->guest->role->name}} </th>
                                            @endif
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
                                            @if($loop->first)
                                                @foreach($feedbackFormsPDF->where('id', $feedbackForm->id) as $form)
                                                    <td>{{$form->avg[$loop->parent->parent->index]}}</td>
                                                @endforeach
                                                <td>{{$answer->answer}}</td>
                                            @else
                                                <td>{{$answer->answer}}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <br><br>
                            <!-- table with comments -->
                            <table class="table" style="  table-layout:fixed; width:100%;">
                                <thead>
                                <tr>
                                    <th scope="col">Questions</th>
                                    @foreach($feedbackForm->answerForms as $answerForm)
                                        @if($answerForm->guest == NULL)
                                            <th scope="col">{{$answerForm->user->name}}
                                                - <br>{{$answerForm->user->role->name}}
                                                @if($answerForm->user->role_verified)
                                                    <div class="verified"></div>
                                                @else
                                                    <div class="not_verified"> x</div>
                                                @endif
                                            </th>
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
                                            <td>{{$answer->comment}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <!-- Secondary buttons for edit and delete a form -->
                            <div>
                                <div></div>
                                <a class="btn pull-right" style="border-color: #3b82f6"
                                   href="/feedbackForm/{{$binder->public_id}}/edit">Edit</a>
                                <div style="display:inline-block">
                                    <form method="POST" action="{{route('feedbackForm.destroy', $binder->public_id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn pull-right" type="submit"
                                                onclick="return confirm('Are you sure you want to delete this form?')"
                                                style="border-color: #3b82f6">Delete this form
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- Form error when form does not exists -->
                            @else
                                <div class="danger">
                                    <h3>Uh oh! <br><br></h3>
                                    <h4>
                                        It looks like something went wrong in the making of this form!
                                        It is best to delete this form and try again. If the problem persists, we
                                        fucked up.<br>
                                    </h4>

                                    <form method="POST"
                                          action="{{route('feedbackForm.destroy', $binder->public_id) }}">@method('DELETE') @csrf
                                        <button class="btn pull-right" type="submit" style="border-color: #3b82f6">
                                            Delete this form
                                        </button>
                                    </form>
                                </div>
                            @endif
                            @else
                                You don't have permission to view this Form.
                            @endif
                        </div>
                    </div>
        </div>
    </div>

@if($binder->form_count == $formCount )

    @foreach($feedbackFormsPDF as $form)

        <!-- ALERT! this code part will only be in the pdf -->

            <!-- Everything inside this class will be in the PDF -->
            <div style="width: 1200px;   position: absolute; left: -10000px; display: inline-block;"
                 class="canvas_div_pdf{{$form->id}}" id="clipped">
                @if(Auth::user()->id == $binder->user_id)
                    <br>

                    <div class="row">
                        <div class="col-md-6 text-left"><h3>{{$form->title}}</h3></div>
                        @if($loop->first)
                            <div class="col-md-6 text-right">{{ $binder->created_at->format('m/d/Y')}}</div>
                        @endif
                    </div>
                    <br>

                    <div class="container">
                        <canvas id="myChart{{$form->id}}"
                                style="margin-bottom: 200px; width:1110px; height:740px"></canvas>
                    </div>

                    <!-- table with answer information PDF -->
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Questions</th>
                            @foreach($form->answerForms as $answerForm)
                                @if($loop->first)
                                    <th scope="col">Average</th>
                                    @if($answerForm->guest == NULL)
                                        <th scope="col">{{$answerForm->user->name}}
                                            - <br>{{$answerForm->user->role->name}}
                                            @if($answerForm->user->role_verified)
                                                <div class="verified"></div>
                                            @else
                                                <div class="not_verified"> x</div>
                                            @endif
                                        </th>
                                    @else
                                        <th scope="col">{{$answerForm->guest->name}}
                                            - {{$answerForm->guest->role->name}} </th>
                                    @endif
                                @else
                                    @if($answerForm->guest == NULL)
                                        <th scope="col">{{$answerForm->user->name}}
                                            - <br>{{$answerForm->user->role->name}}
                                            @if($answerForm->user->role_verified)
                                                <div class="verified"></div>
                                            @else
                                                <div class="not_verified"> x</div>
                                            @endif
                                        </th>
                                    @else
                                        <th scope="col">{{$answerForm->guest->name}}
                                            - {{$answerForm->guest->role->name}} </th>
                                    @endif
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
                                    @if($loop->first)
                                        <td>{{$form->avg[$loop->parent->parent->index]}}</td>
                                        <td>{{$answer->answer}}</td>
                                    @else
                                        <td>{{$answer->answer}}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <br><br>
                    <!-- table with comments -->
                    <table class="table" style="  table-layout:fixed; width:100%;">
                        <thead>
                        <tr>
                            <th scope="col">Questions</th>
                            @foreach($form->answerForms as $answerForm)
                                @if($answerForm->guest == NULL)
                                    <th scope="col">{{$answerForm->user->name}}
                                        - <br>{{$answerForm->user->role->name}}
                                        @if($answerForm->user->role_verified)
                                            <div class="verified"></div>
                                        @else
                                            <div class="not_verified"> x</div>
                                        @endif
                                    </th>
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
                                    <td>{{$answer->comment}}</td>
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
    @endif
</x-app-layout>

@if($binder->form_count == $formCount)

    <!-- Script for making the Chart.js -->
    <script>
        let color = ['rgba(255, 0, 0, 0.4)', 'rgba(0, 0, 255, 0.4)', 'rgba(0, 204, 255, 0.4)', 'rgba(204, 102, 255, 0.4)', 'rgba(128, 0, 128, 0.4)'];
        let counter = 0;
        let myChart = document.getElementById('myChart').getContext('2d');

        const data = {

            //labels are the questions shown in the form
            labels: [@foreach($feedbackForm->questions as $question)
                '{{$question->question}}',
                @endforeach],

            //the actual data for in the chart
            datasets: [
                    @foreach($feedbackForm->answerForms as $answerForm){

                    //set average in first loop, then iterate over first user
                    @if($loop->first)
                    label: 'average',

                    data: [
                        @foreach($feedbackFormsPDF->where('id', $feedbackForm->id) as $form)
                            @foreach($form->avg as $avg)
                            '{{$avg}}',
                        @endforeach
                        @endforeach

                    ],
                    borderColor: 'black',
                    backgroundColor: '#FF000000',
                    borderWidth: 1.5
                },

                //in loop first, add first user after average has been set
                {
                    @if($answerForm->guest == NULL)
                    label: '{{$answerForm->user->name}}',
                    @else
                    label: '{{$answerForm->guest->name}}',
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
                //after loop first add the rest of the data
                @else
                    @if($answerForm->guest == NULL)
                    label
        :
        '{{$answerForm->user->name}}',
            @else
                label
        :
        '{{$answerForm->guest->name}}',
            @endif

                data
        :
        [
            @foreach($answerForm->answers as $answer)
                '{{$answer->answer}}',
            @endforeach
        ],
            borderColor
        :
        '#777',
            backgroundColor
        :
        `${color[counter++]}`,
            borderWidth
        :
        1
        },
        @endif
        @endforeach
        ]
        }
        ;

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

    <!-- Script to draw the charts on the pdf -->
    <script>
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
        let counter{{$feedbackForm->id}} = 0;
        let myChart{{$feedbackForm->id}} = document.getElementById(`myChart{{$feedbackForm->id}}`).getContext('2d');

        const data{{$feedbackForm->id}} = {
            labels: [
                @foreach($feedbackForm->questions as $question)
                    '{{$question->question}}',
                @endforeach],

            datasets: [
                    @foreach($feedbackForm->answerForms as $answerForm){

                    //set average in first loop, then iterate over first user
                    @if($loop->first)
                    label: 'average',

                    data: [
                        @foreach($feedbackFormsPDF->where('id', $feedbackForm->id) as $form)
                            @foreach($form->avg as $avg)
                            '{{$avg}}',
                        @endforeach
                        @endforeach

                    ],
                    borderColor: 'black',
                    backgroundColor: '#FF000000',
                    borderWidth: 1.5
                },

                //in loop first, add first user after average has been set
                {
                    @if($answerForm->guest == NULL)
                    label: '{{$answerForm->user->name}}',
                    @else
                    label: '{{$answerForm->guest->name}}',
                    @endif

                    data: [
                        @foreach($answerForm->answers as $answer)
                            '{{$answer->answer}}',
                        @endforeach
                    ],
                    borderColor: '#777',
                    backgroundColor: `${color[counter{{$feedbackForm->id}} ++]}`,
                    borderWidth: 1
                },
                //after loop first add the rest of the data
                @else
                    @if($answerForm->guest == NULL)
                    label
        :
        '{{$answerForm->user->name}}',
            @else
                label
        :
        '{{$answerForm->guest->name}}',
            @endif

                data
        :
        [
            @foreach($answerForm->answers as $answer)
                '{{$answer->answer}}',
            @endforeach
        ],
            borderColor
        :
        '#777',
            backgroundColor
        :
        `${color[counter{{$feedbackForm->id}} ++]}`,
            borderWidth
        :
        1
        },
        @endif
        @endforeach
        ]
        }
        ;


        let massPopChart{{$feedbackForm->id}} = new Chart(myChart{{$feedbackForm->id}}, {
            type: 'radar',
            options: optionsPDF,
            data: data{{$feedbackForm->id}},
        });
        @endforeach
    </script>

    <!-- Script for making the PDF download -->
    <script>
        function getPDF() {
            $('html,body').scrollTop(0);
            console.log('generating pdf')


            let pdf = ''
            @foreach($feedbackFormsPDF as $feedbackForm)
            var HTML_Width = document.querySelector(".canvas_div_pdf{{$feedbackForm->id}}").getBoundingClientRect().width;
            var HTML_Height = document.querySelector(".canvas_div_pdf{{$feedbackForm->id}}").getBoundingClientRect().height;
            console.log(HTML_Width, HTML_Height)

            var top_left_margin = 15;
            // var PDF_Width = HTML_Width + (top_left_margin * 2);
            // var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
            var PDF_Width = HTML_Width + 30;
            var PDF_Height = HTML_Height + 30;
            var canvas_image_width = HTML_Width;
            var canvas_image_height = HTML_Height;
            html2canvas($(".canvas_div_pdf{{$feedbackForm->id}}")[0], {
                allowTaint: true,
                scale: 2
            }).then(function (canvas) {
                canvas.getContext('2d');
                var imgData = canvas.toDataURL("image/jpeg", 1.0);
                @if ($loop->first)
                    pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
                console.log('first page generated')
                @else
                pdf.addPage(PDF_Width, PDF_Height);
                console.log('page generated')
                @endif

                pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);

                @if($loop->last)
                console.log('saving pdf')
                pdf.save("{{$binder->title}}.pdf");
                @endif

            });
            @endforeach
        }
    </script>

    <!-- Make PDf invisible on page -->
    <style>
        #clipped {
            clip-path: inset(0 100% 0 0);
        }
    </style>
@endif

