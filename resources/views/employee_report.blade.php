@php
    $msg = '';
    if (!empty($fromDate) && !empty($toDate)) {
        $msg = 'Report List from ' . $fromDate . ' to ' . $toDate;
    } elseif (!empty($fromDate)) {
        $msg = 'Report List from:- ' . $fromDate;
    } else {
        $msg = 'Report List for:-' . $toDate;
    }
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Monthly Employee Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #336699;
            margin-top: 20px;
        }

        p {
            text-align: center;
            color: #777;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #473030;
        }
    </style>
</head>

<body>
    <h1>
        @if (!empty($username))
            {{ $username }} 's
        @else
            All Employees
        @endif Monthly Report List
    </h1>
    <p> {{ $msg }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>CheckIn</th>
                <th>CheckOut</th>
                <th>Working Hours</th>
                <th>Total Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->date }}</td>
                    <td>{{ $report->user->username }}</td>
                    <td>{{ \App\Traits\Base::timeParse($report->check_in) }}</td>
                    <td>{{ \App\Traits\Base::timeParse($report->check_out) }}</td>

                    <td>
                        @if (!empty($report->net_work_hours))
                            {{ $report->net_work_hours }}
                        @endif
                    </td>
                    <td>
                        @if (!empty($report->check_in) && !empty($report->check_out))
                            @php
                                $total_duration = \App\Traits\Base::convertDateTime($report->check_in, $report->check_out);
                            @endphp
                            {{ $total_duration }}
                        @endif
                    </td>
                </tr>
                @if ($report->breakTasks != null)
                    @foreach ($report->breakTasks as $br_report)
                        <tr>
                            <td>{{ $report->user->username }} Break time</td>
                            <td>{{ $report->user->username }}</td>
                            <td>
                                @if (!empty($br_report->break_start))
                                    {{ \App\Traits\Base::timeParse($br_report->break_start) }}
                                @endif
                            </td>
                            <td>
                                @if (!empty($br_report->break_end))
                                    {{ \App\Traits\Base::timeParse($br_report->break_end) }}
                                @endif
                            </td>
                            <td>
                                @if (!empty($br_report->break_start) && !empty($br_report->break_end))
                                    @php
                                        $duration = \App\Traits\Base::convertDateTime($br_report->break_start, $br_report->break_end);
                                    @endphp
                                    {{ $duration }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
    <br>
    <br>
</body>
</body>

</html>
