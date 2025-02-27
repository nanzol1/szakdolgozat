<!DOCTYPE html>
<html>
<head>

</head>
<body>
    @foreach ($cv as $education)
        <p>
            <strong>{{ $education['degree'] }}</strong><br>
            {{ $education['school_name'] }}<br>
            {{ $education['startdate'] }} – {{ $education['enddate'] }}
        </p>
    @endforeach
</body>
</html>