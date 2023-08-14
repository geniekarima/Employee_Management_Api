<html>
<head>
    <title>Employee Report List</title>
</head>
<body>
    <h1>Employee Report List</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>CheckIn</th>
                <th>CheckOut</th>
                <th>Office_hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->date }}</td>
                    <td>{{ $report->check_in }}</td>
                    <td>{{ $report->check_out }}</td>
                    <td>{{ $report->office_hours }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
