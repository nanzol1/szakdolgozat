<!DOCTYPE html>
<html>
<head>

</head>
<body>
    @foreach ($cv as $education)
        <p>
            {{ $education }}
        </p>
    @endforeach
</body>
</html>