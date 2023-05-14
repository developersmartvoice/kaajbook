<!DOCTYPE html>
<html>

<head>
    <title></title>
    <style type="text/css">
    .center {
        margin: auto;
        padding: 10px;
    }

    table {
        border-collapse: collapse;
    }

    table,
    td,
    th {
        border: 1px solid black;
    }
    </style>
</head>

<body>
    <div class="center">
        <p>
            Hello there,
            <br /><br />
            Please Find the Employee Tasks Reporting for {{ date('d F Y, l') }}.
        </p>
        <div>
            <table width="100%">
                <thead>
                    <tr bgcolor="#708090">
                        <th>{{ trans('messages.timesheet.columns.entry') }}</th>
                        <th>{{ trans('messages.timesheet.columns.start_time') }}</th>
                        <th>{{ trans('messages.timesheet.columns.end_time') }}</th>
                        <th>{{ trans('messages.timesheet.columns.time_h') }}</th>
                        <th>{{ trans('messages.timesheet.columns.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php ($current_user = 0)
                    @forelse ($timesheets as $timesheet)
                    @if ($current_user != $timesheet->created_user_id)
                    @php ($current_user = $timesheet->created_user_id)
                    <tr bgcolor="#D3D3D3">
                        <td colspan="5">
                            <div style="text-align: center;">
                                <strong><a href="{{$front_url}}#/users/profile/{{$timesheet->user->id}}">{{$timesheet->user->firstname}} {{$timesheet->user->lastname}}</a> - Total Hours :: {{$user_hours[$timesheet->created_user_id]}}</strong>
                            </div>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>
                            @if ($timesheet->module_id == 2)
                                <a href="{{$front_url}}#/tasks/detail/{{$timesheet->module_related_id}}">#{{$timesheet->task_generated_id}}</a> -  {{$timesheet->task_name}}
                            @elseif ($timesheet->module_id == 3)
                                <a href="{{$front_url}}#/defects/detail/{{$timesheet->module_related_id}}">#{{$timesheet->defect_generated_id}}</a> -  {{$timesheet->defect_name}}
                            @elseif ($timesheet->module_id == 4)
                                <a href="{{$front_url}}#/incidents/detail/{{$timesheet->module_related_id}}">#{{$timesheet->incident_generated_id}}</a> - {{$timesheet->incident_name}}
                            @elseif ($timesheet->module_id == 5)
                                <a href="{{$front_url}}#/meetings/detail/{{$timesheet->module_related_id}}">#{{$timesheet->meeting_generated_id}}</a> - {{$timesheet->meeting_title}}
                            @elseif ($timesheet->module_id == 6)
                            -
                            @elseif ($timesheet->module_id == 7)
                                {{$timesheet->leave_title}}
                            @endif
                            @if($timesheet->note)
                                (Note: {{ $timesheet->note }})
                            @endif
                        </td>
                        <td>{{ date('d-m-Y h:i A', strtotime($timesheet->start_time)) }}</td>
                        <td>{{ date('d-m-Y h:i A', strtotime($timesheet->end_time)) }}</td>
                        <td>{{$timesheet->hour_time}}</td>
                        <td>
                            @if ($timesheet->module_id == 2)
                            {{$task_status[$timesheet->task_status]}}
                            @elseif ($timesheet->module_id == 3)
                            {{$defect_status[$timesheet->defect_status]}}
                            @elseif ($timesheet->module_id == 4)
                            {{$incident_status[$timesheet->incident_status]}}
                            @elseif ($timesheet->module_id == 5)
                            {{$meeting_status[$timesheet->meeting_status]}}
                            @elseif ($timesheet->module_id == 7)
                            {{$leave_status[$timesheet->leave_status]}}
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center">{{ trans('messages.msg_1') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p>Best Regards<br/>{{$app_name}}</p>
    </div>
</body>

</html>