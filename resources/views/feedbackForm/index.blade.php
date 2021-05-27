<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if(Auth::user()->role_id != NULL)
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <x-button class="ml-3" onclick="document.location.href='{{route('feedbackForm.create')}}'">
                            Create Form
                        </x-button>
                        <div class="row">
                            @foreach($formBinders as $binder)
                                <div class="col-lg-5 col-md-12 col-sm-12 bg-light"
                                     style="padding: 20px; border-radius: 25px; margin: 40px">
                                    <a href="/feedbackForm/{{$binder->public_id}}" style="color: inherit;">
                                        <h3 class="feature-title">{{$binder->title}}</h3>

                                        <div class="container">
                                            @foreach($binder->feedbackForms as $form)
                                                @if($loop->first)
                                                    <canvas id="myChart{{$form->id}}" width="200px" height="120px"
                                                    style="margin-bottom: 50px"></canvas>
                                                @endif
                                            @endforeach
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        {{ $formBinders->links() }}
                    @else
                        Before you can start making feedback forms you need to edit your account <br>
                        <x-button class="ml-3" onclick="document.location.href='{{route('user.index')}}'">
                            Edit account
                        </x-button>
                    @endif
                </div>
            </div>
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
            display: false,
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

    @foreach($formBinders as $binder)
        @foreach($binder->feedbackForms as $feedbackForm)
            @if($loop->first)
    let counter{{$feedbackForm->id}} = 0;
    let myChart{{$feedbackForm->id}} = document.getElementById(`myChart{{$feedbackForm->id}}`).getContext('2d');

    const data{{$feedbackForm->id}} = {
        labels: [
            @foreach($feedbackForm->questions as $question)
                '',
            @endforeach
        ],
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
            @endif
        @endforeach
    @endforeach
</script>
