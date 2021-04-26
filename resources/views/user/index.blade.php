<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('New Feedback Form') }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div>{{$user->name}}</div>
                <div>{{$user->email}}</div>
                @if($user->role_id == NULL)
                    <div>Not specified</div>
                @else
                <div>{{$user->role->name}}</div>
                @endif

                <button onclick="location.href='/user/{{$user->id}}/edit'" type="button" style="float: left">Edit</button>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
