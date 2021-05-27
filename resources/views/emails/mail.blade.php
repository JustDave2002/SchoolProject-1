<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
      crossorigin="anonymous">
{{--<h1>HZ 360° Feedback tool</h1>--}}
<body style="background-color: #e6e6e6">
<img style='margin-left: 250px' src="https://i.imgur.com/QLIdRkh.png" height="100 px">
<div style="background-color: white; border-radius: 10px; padding: 15px; margin: 30px; margin-top: 0px">

    <H2>Hello, {{$name}} wants feedback from you!</H2>
    <p><b>{{$name}}</b> wants to get feedback from
        you, with the help from
        the HZ 360° Feedback tool. With this tool you can ask and give feedback. As long as you have the e-mail address
        of a
        person you can ask for feedback.
        By clicking on the "give feedback" button below you can give feedback.
        <br>
        <br>
        <b>Our Concept</b>
        <br>
        This tool was made with the goal to simply give and receive feedback within a 360º tool. To reach this goal, we
        want
        the system to meet our different specific requirements. With the 360º feedback tool, you are able to make
        feedback
        forms and receive feedback from several people. In best case these people are students, teachers, supervisors
        and
        guests. Both the student and the teacher should be able to create 360º feedback forms. The questions on which a
        person wants to be judged should be customized to that person wanted criteria. The Feedback Tool is meant for
        general questions. It has to give a global view of criteria and should not go into specific deeply answered
        questions. After creating a feedback form you can invite people via mail, so they can give their feedback. The
        feedback will be given by using a five point scale system. After all people have given their feedback a PDF file
        can
        be generated in which all your results will be shown.</p>
    @if( $guest==NULL)
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="border-radius: 2px;" bgcolor="#3b82f6">
                                <a href="https://hz360feedback.herokuapp.com/answer/info/{{$public_id}}" target="_blank"
                                   style="padding: 8px 12px; border: 1px solid #3b82f6;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
                                    Give feedback
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @else
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="border-radius: 2px;" bgcolor="#3b82f6">
                                <a href="https://hz360feedback.herokuapp.com/guestAnswer/info/{{$public_id}}" target="_blank"
                                   style="padding: 8px 12px; border: 1px solid #3b82f6;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
                                    Give feedback
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endif
</div>
<div style="padding-top: 20px"></div>
</body>


