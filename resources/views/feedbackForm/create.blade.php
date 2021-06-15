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
                    <form method="POST" action="{{route('feedbackForm.store')}}" class="was-validated">
                        @csrf

                        <div>
                            <label class="container" style="padding: 0px; margin: 0px" for="checkBox"> <h5 style="margin-bottom: 5px">Do you want a collection of multiple feedback forms?</h5>
                                <a>Each form has a total of 6 questions.</a>
                            <br>
                            <input type="checkbox" id="checkBox" onclick="showElement()">
                            <span class="checkmark"></span>
                            </label>
                        </div>

                        <div class='categoryForm' style="display: none">
                            <br>
                            <div>
                                <label style="margin: 0px" for="title">Form Title</label><br>
                                <input type="text" id="title" class="form-control" placeholder="Enter Title" name="title" value="Placeholder Title" maxlength="150" required>
                                <div class="valid-feedback"><br></div>
                                <div class="invalid-feedback">Please fill out this field.</div>

                                <label for="form_count">Amount of forms</label>
                                <div>
                                <select id="dropdown" class="block mt-1 w-full" style="margin-bottom: 30px" name="form_count" onclick="onClickDropdown()"  required />

                                <option style="display: none" value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <input class="btn btn-primary" style="width: 100%" type="submit" value="Next">
                        <br>
                        <br>
                    </form>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script type="text/javascript">
    function showElement() {
        const element = document.querySelector('.categoryForm');
        const dropdown = document.getElementById('dropdown');
        const checkbox = document.getElementById("checkBox");
        const title = document.getElementById("title");

        if (checkbox.checked === true) {
            element.style.visibility = 'visible';
            element.style.display = 'block';
            title.value = '';
            dropdown.value = 2;

        }

        if (checkbox.checked === false) {
            element.style.visibility = 'hidden';
            element.style.display = 'none';
            title.value = 'Placeholder title';
            dropdown.value = 1;
        }
    }

    let dropdownValue = 1;

    function onClickDropdown() {
        let dropdown = document.getElementById("dropdown");
        dropdownValue = dropdown.value;
        console.log(dropdownValue);
    }
</script>
<style>
    label {
        display: block;
    }
    label input[type="checkbox"] {
        display: none;
    }
    label input[type="checkbox"] ~ .checkmark {
        position: relative;
        display: inline-block;
        top: 0;
        left: 0;
        /*margin-top: 15px;*/
        height: 35px;
        width: 35px;
        border-radius: 5px;
        background-color: #eee;
    }
    label input[type="checkbox"]:checked ~ .checkmark {
        background-color: #007bff;
    }
    /* On mouse-over, add a grey background color */
    .container:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .container input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .container .checkmark:after {
        left: 13px;
        top: 5px;
        width: 10px;
        height: 20px;
        border: solid white;
        border-width: 0 4px 4px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
</style>
