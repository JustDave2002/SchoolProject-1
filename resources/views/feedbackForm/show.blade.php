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
                <div>{{$feedbackForm->title}}</div>
                <div>{{$feedbackForm->q1}}</div>
                <div>{{$feedbackForm->q2}}</div>
                <div>{{$feedbackForm->q3}}</div>
                <div>{{$feedbackForm->q4}}</div>
                <div>{{$feedbackForm->q5}}</div>
                <div>{{$feedbackForm->q6}}</div>

                    @else
                        You don't have permission to view this article.
                @endif

            </div>
        </div>
    </div>
</div>
</x-app-layout>
