<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
      crossorigin="anonymous">
<h1>Hello!</h1>
<img src="https://i.imgur.com/mXPaedC.png" width="70" , height="70">
<H2>Do you want to give me feedback?</H2>
@if( $guest==NULL)
    <a href="http://127.0.0.5/answer/info/{{$id}}">Give feedback account</a>
@else
    <a href="http://127.0.0.5/guestAnswer/info/{{$id}}">Give feedback</a>
@endif

