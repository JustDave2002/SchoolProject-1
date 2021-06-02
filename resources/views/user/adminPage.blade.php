<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">
        <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Page') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4>Incoming Role Requests</h4>
                    <br>
                    <!-- table with answer information -->
                    <div id="users">
                        <input class="search" placeholder="Search"/>
                        <a>
                            Sort by name
                        </a>
                        <table class="table">
                            <thead class="thead">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Name</th>
                                <th scope="col" class="sort" data-sort="email">Email</th>
                                <th scope="col" class="sort" data-sort="role">Requested Role</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody class="tbody list">
                            @foreach($notVerifiedUsers as $user)
                                <tr>
                                    <th scope="row" class="name"> {{$user->name}} </th>
                                    <td class="email"> {{$user->email}} </td>
                                    <td class="role"> {{$user->role->name}}</td>
                                    <td>
                                        <form method="get"
                                              action="{{route('adminPage.verified', ['id' => $user->id])}}">
                                            <button class="btn btn-primary" href="" style="margin-right: 24px">Accept
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="get"
                                              action="{{route('adminPage.declined', ['id' => $user->id])}}">
                                            <button class="btn pull-right" style="border-color: #3b82f6;">Decline
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4>All Users</h4>
                    <br>
                    <!-- table with answer information -->
                    <div id="user">
                        <input class="search" placeholder="Search"/>
                        <a>
                            Sort by name
                        </a>
                        <table class="table">
                            <thead class="thead">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Name</th>
                                <th scope="col" class="sort" data-sort="email">Email</th>
                                <th scope="col" class="sort" data-sort="role"> Role</th>
                                <th scope="col" class="sort" data-sort="admin">Admin</th>
                                <th scope="col" class="sort" data-sort="verified_mail">Email Verified</th>
                            </tr>
                            </thead>
                            <tbody class="list tbody">
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row" class="name"> {{$user->name}} </th>
                                    <td class="email"> {{$user->email}} </td>
                                    <td class="role">
                                        @if($user->role_id == NULL)
                                            not specified
                                        @else
                                            {{$user->role->name}}
                                        @endif
                                    </td>
                                    <td class="admin">
                                        @if($user->admin == FALSE)
                                            No
                                        @else
                                            Yes
                                        @endif
                                    </td>
                                    <td class="email_verified">
                                        @if($user->email_verified == NULL)
                                            not verified
                                        @else
                                            verified
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

<script>
    const options = {
        valueNames: ['name', 'email', 'role', 'admin', 'email_verified']
    };

    let userLists = new List('users', options);
    let userList = new List('user', options);
</script>
<style>
    .table td:nth-child(n+2) {
        border: 2px solid black;
    }

    .table td:nth-child(n+2) {
        border: 2px solid black;
    }

    .table td:nth-last-child(n) {
        border-right: 0px
    }


    .table th:nth-child(n+2) {
        border-bottom: 3px solid black;
        border-left: 2px solid black;
        border-right: 2px solid black;
    }

    .table th:nth-last-child(n) {
        border-right: 0px
    }

    .thead th:nth-child(1) {
        border-bottom: 3px solid black;
        border-right: 2px solid black
    }

    .thead {
        background-color: #c5cdd3;
    }

    .tbody th:nth-child(1) {
        border-bottom: 2px solid black;
    }

    .tbody tr:nth-child(even) {
        background-color: #e2e6e9;
    }

    input {
        border: solid 1px #ccc;
        border-radius: 5px;
        padding: 7px 14px;
        margin-bottom: 10px
    }

    input:focus {
        outline: none;
        border-color: #aaa;
    }

    .sort {
        /*padding:8px 30px;*/
        /*border-radius: 6px;*/
        /*border:none;*/
        /*display:inline-block;*/
        color: black;
        text-decoration: none;

        height: 30px;
    }

    .sort:focus {
        outline: none;
    }

    .sort:after {
        display: inline-block;
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid transparent;
        content: "";
        position: relative;
        top: -10px;
        right: -5px;
    }

    .sort.asc:after {
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #fff;
        content: "";
        position: relative;
        top: 4px;
        right: -5px;
    }

    .sort.desc:after {
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #fff;
        content: "";
        position: relative;
        top: -4px;
        right: -5px;
    }
</style>
