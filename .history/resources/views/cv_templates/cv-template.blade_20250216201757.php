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
    <h1>{{ $cv->personal_info['name'] }}</h1>
    <div class="section">
        <h2>Skills</h2>
        <ul>
            @foreach ($cv->skills as $skill)
                <li>{{ $skill['title'] }}</li>
            @endforeach
        </ul>
    </div>
    <div class="section">
        <h2>Education</h2>
        <p>{{ $cv->education['degree'] }} at {{ $cv->education['institution'] }}</p>
    </div>
    <div class="section">
        <h2>Experience</h2>
        @foreach ($cv->experience as $experience)
            <p>{{ $experience['title'] }} at {{ $experience['employer'] }} ({{ $experience['startdate'] }} - {{ $experience['enddate'] }})</p>
        @endforeach
    </div>
</body>
</html>