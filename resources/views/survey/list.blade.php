
<!doctype html>
<html lang="en">
<head>
</head>
<body>
<!-- Add the search form -->
<form action="/" method="GET">
    <input type="text" name="search" value="{{ $searchQuery ?? '' }}" placeholder="Search surveys...">
    <button type="submit">Search</button>
</form>

<!-- Display the survey list -->
@if (!empty($surveys))
<ul>
    @foreach ($surveys as $survey)
        <li>
            @if (isset($survey['survey']))
                @if (isset($survey['survey']['name']))
                    <h3>{{ $survey['survey']['name'] }}</h3>
                @endif

                @if (isset($survey['survey']['code']))
                    <p>Code: {{ $survey['survey']['code'] }}</p>
                @endif
            @endif

            @if (isset($survey['questions']))
                @foreach ($survey['questions'] as $question)
                    @if (isset($question['type']))
                        <h4>{{ $question['type'] }}</h4>
                    @endif

                    @if (isset($question['label']))
                        <h5>{{ $question['label'] }}</h5>
                    @endif

                    @if ($question['type'] === 'qcm')
                        @if (isset($question['options']) && isset($question['answer']))
                            <h6>Options:</h6>
                            <ul>
                                @foreach ($question['options'] as $index => $option)
                                    <li>{{ $option }}: {{ $question['answer'][$index] }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @elseif ($question['type'] === 'numeric')
                        @if (isset($question['answer']))
                            <p>Result: {{ $question['answer'] }}</p>
                        @endif
                    @endif
                @endforeach
            @endif
        </li>
    @endforeach
</ul>
@else
    <p>No surveys found.</p>
@endif
</body>
</html>
