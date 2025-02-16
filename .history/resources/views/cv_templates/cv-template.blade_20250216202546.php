<!DOCTYPE html>
<html>
<head>
    <title>CV - {{ $cv->personal_info['name'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .header p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 18px;
            color: #333;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .section p {
            margin: 5px 0;
        }
        .section ul {
            list-style-type: none;
            padding: 0;
        }
        .section ul li {
            margin-bottom: 5px;
        }
        .section ul li::before {
            content: "●";
            color: #333;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Fejléc -->
        <div class="header">
            <h1>{{ $cv->firstname }}</h1>
            <p>{{ $cv->email }} • {{ $cv->phone }}</p>
            <p>{{ $cv->address }}, {{ $cv->zip }}</p>
        </div>

        <!-- Összegzés -->
        <div class="section">
            <h2>Summary</h2>
            {{ $cv->more_desc }}
        </div>

        <!-- Tapasztalat -->
        <div class="section">
            <h2>Experience</h2>
            @foreach ($cv->experience as $experience)
                <p>
                    <strong>{{ $experience['title'] }}, {{ $experience['employer'] }}</strong><br>
                    {{ $experience['startdate'] }} – {{ $experience['enddate'] }}<br>
                    {{ $experience['description'] }}
                </p>
            @endforeach
        </div>

        <!-- Oktatás -->
        <div class="section">
            <h2>Education</h2>
            @foreach ($cv->education as $education)
                <p>
                    <strong>{{ $education['degree'] }}</strong><br>
                    {{ $education['institution'] }}<br>
                    {{ $education['startdate'] }} – {{ $education['enddate'] }}
                </p>
            @endforeach
        </div>

        <!-- Készségek -->
        <div class="section">
            <h2>Skills</h2>
            <ul>
                @foreach ($cv->skills as $skill)
                    <li>{{ $skill['title'] }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>