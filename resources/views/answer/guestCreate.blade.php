<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fill in the feedback') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{route('guestAnswer.updateCreate')}}" class="was-validated">
                        @csrf
                        @if(Auth::check() == 0)
                            <div class="form-group">
                                <label for="name">Name</label><br>
                                <input type="text" id="name" class="form-control" placeholder="Enter your name" name="name"  required>
                                <div class="valid-feedback"><br></div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form-group">
                                <label for="title">Function</label><br>

                                <select id="role_id" class="block mt-1 w-full" style="margin-bottom: 40px" name="role_id"  required />
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                    </select>
                            </div>
                        @endif
                        <br>
                        <br>
                        <input class="btn btn-primary" style="width: 100%" type="submit" value="Submit">
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
