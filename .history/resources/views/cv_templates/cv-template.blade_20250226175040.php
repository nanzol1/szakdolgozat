<!DOCTYPE html>
<html>
<head>

<body>
    <div class="container">
        <img src="{{ public_path('storage/uploads/cv_maker/' . $cv->cv_picture) }}" alt="Image">
    </div>
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
            {!! clean($cv->more_desc) !!}
        </div>

        <!-- Tapasztalat -->
        <div class="section">
            <h2>Experience</h2>
            @foreach ($cv->exps as $experience)
                <p>
                    <strong>{{ $experience['title'] }}, {{ $experience['employer'] }}</strong><br>
                    {{ $experience['startdate'] }} – {{ $experience['enddate'] }}<br>
                    <div>
                    {!! clean($experience['description']) !!}
                    </div>
                </p>
            @endforeach
        </div>

        <!-- Oktatás -->
        <div class="section">
            <h2>Education</h2>
            @foreach ($cv->educations as $education)
                <p>
                    <strong>{{ $education['degree'] }}</strong><br>
                    {{ $education['school_name'] }}<br>
                    {{ $education['startdate'] }} – {{ $education['enddate'] }}
                </p>
            @endforeach
        </div>

        <!-- Készségek -->
        <div class="section">
            <h2>Skills</h2>
            <ul>
                @foreach ($cv->skills as $skill)
                    <li>{{ $skill['skill_name'] }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>