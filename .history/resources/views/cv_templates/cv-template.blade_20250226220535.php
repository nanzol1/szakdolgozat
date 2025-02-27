<!DOCTYPE html>
<html>
<head>

</head>
<body>
    {{ $cv }}
    @foreach ($cv as $item)
        {{ $item }}
    @endforeach
</body>
</html>