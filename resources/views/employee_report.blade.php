{{-- <table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Employee</th>
            <th>CheckIn</th>
            <th>CheckOut</th>
            <th>Office_hours</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $report->date }}</td>
            <td>{{ $report->user->username }}</td>
            <td>{{ $report->check_in}}</td>
            <td>{{ $report->check_out}}</td>
            <td>{{ $report->office_hours}}</td>
        </tr>
    </tbody>
</table> --}}
<!DOCTYPE html>
<html>

<head>
    <title>Monthly Employee Report</title>
</head>

<body>
    <h1>Monthly Employee Report</h1>
    <p>Report from {{ $fromDate }} to {{ $toDate }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>CheckIn</th>
                <th>CheckOut</th>
                <th>Office_hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->date }}</td>
                    <td>{{ $report->user->username }}</td>
                    <td>{{ $report->check_in }}</td>
                    <td>{{ $report->check_out }}</td>
                    <td>{{ $report->office_hours }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
