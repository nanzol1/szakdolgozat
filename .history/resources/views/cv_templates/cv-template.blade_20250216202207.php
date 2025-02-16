<!DOCTYPE html>
<html>
<head>
    <title>CV</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
        .section { margin-bottom: 20px; }
        .section h2 { color: #555; }
        .section p { margin: 5px 0; }
    </style>
</head>
<body>
    <h1>{{ $cv }}</h1>
    <div class="section">
        <h2>Skills</h2>
    </div>
    <div class="section">
        <h2>Education</h2>
        <p>{{ $cv}} at {{ $cv }}</p>
    </div>
    <div class="section">
        <h2>Experience</h2>
    </div>
</body>
</html>