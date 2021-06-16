<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
              integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
              crossorigin="anonymous">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit account details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="/user/{{ $user->id }}" class="was-validated">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label><br>
                            <input type="text" id="name" class="form-control" placeholder="Enter your name" name="name"
                                   value="{{Auth::user()->name}}" required maxlength="50">
                            <div class="valid-feedback"><br></div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                        <!-- Function -->
                        <div class="form-group">
                            <label for="title">Function</label><br>
                            <a id="error-message"
                               style="color: red; display: @if(Auth::user()->role_id == 1)none @else block @endif">Functions
                                other than Student must be
                                verified.</a>
                            <select id="role_id" class="block mt-1 w-full" style="margin-bottom: 30px" name="role_id"
                                    required onchange="test(this);"/>
                            @foreach($roles as $role)
                                @if($role->id == Auth::user()->role_id)
                                    <option value="{{$role->id}}" selected="selected">{{$role->name}}</option>
                                @else
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endif
                            @endforeach
                            <div style='margin-top: 10%'></div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <input class="btn btn-primary" style="width: 100%" type="submit" value="Submit">
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>


    window.test = function (e) {
        const error = document.getElementById('error-message')

        @foreach($roles as $role)
            @if($loop->first)
        if (e.value === '{{$role->id}}') {
            console.log(e.value);
            error.style.display = 'none';
        }
            @else
        else if (e.value === '{{$role->id}}') {
            console.log(e.value);
            error.style.display = 'block';
        }
        @endif
        @endforeach
    }
</script>
