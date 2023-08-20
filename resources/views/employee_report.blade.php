@php
    $msg = "";
    if (!empty($fromDate) && !empty($toDate)) {
        $msg = 'Report List from ' . $fromDate . ' to ' . $toDate;
    } elseif (!empty($fromDate)) {
        $msg = 'Report List from:- ' . $fromDate;
    }else{
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

        th, td {
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
    <h1>@if(!empty($username)){{$username}} 's @else All Employees @endif Monthly Report List</h1>
    <p> {{ $msg }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>CheckIn</th>
                <th>CheckOut</th>
                <th>Office Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->date }}</td>
                    <td>{{ $report->user->username }}</td>
                    <td>{{ $report->check_in }}</td>
                    <td>{{ $report->check_out }}</td>
                    <td>@if(!empty($report->net_work_hours)){{ $report->net_work_hours }}@endif</td>
                    {{-- <td>{{ $report->office_hours }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
