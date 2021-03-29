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
                    You're logged in!
                    <div class="row">
                    @foreach(Auth::user()->feedbackForms as $form)
                        <div class="col-lg-5 col-md-12 col-sm-12 bg-light"
                             style="padding: 20px; border-radius: 25px; margin: 40px" >
                            <a href="/feedbackForm/{{$form->id}}" style="color: inherit;">
                                <h3 class="feature-title">{{$form->title}}</h3>

                                <img src="https://www.zohowebstatic.com/sites/default/files/web.png" class="img-fluid" style="height: 200px">
{{--                                <img src="{{$form->image}}" class="img-fluid" style="height: 200px">--}}
                            </a>
                        </div>
                    @endforeach
                    </div>
                    <button ><a href="{{route('feedbackForm.create')}}">create</a></button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
