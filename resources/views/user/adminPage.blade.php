<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- table with answer information -->
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Requested Role</th>
                            <th scope="col">Verification</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($notVerifiedUsers as $user)
                            <tr>
                                <th scope="row"> {{$user->name}} </th>
                                <td> {{$user->email}} </td>
                                <td> {{$user->role->name}}</td>
                                <td><button class="btn btn-primary" href="" style="margin-right: 24px">Accept</button><button class="btn pull-right"  style="border-color: #3b82f6;"  >Decline</button></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

