<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class SurveyController extends Controller
{
    public function getAggregationByCode(Request $request, $code){
        $dataPath = storage_path('app/data');
        $files = File::allFiles($dataPath);

        $aggregation = [];

        foreach ($files as $file) {
            $jsonData = File::get($file);
            $surveyData = json_decode($jsonData, true);

            if ($surveyData['survey']['code'] === $code) {
                $this->aggregateAnswers($surveyData['questions'], $aggregation);
            }
        }

        if (empty($aggregation)) {
            return response()->json(['error' => 'Survey not found'], 404);
        }

        return response()->json($aggregation);
    }

    private function aggregateAnswers($questions, &$aggregation){
        foreach ($questions as $question) {
            $type = $question['type'];
            $answer = $question['answer'];

            if ($type === 'qcm') {
                // Aggregation logic for qcm type
                // Example: Count the number of true/false answers
                $options = $question['options'];

                foreach ($options as $index => $option) {
                    if (!isset($aggregation[$option])) {
                        $aggregation[$option] = 0;
                    }

                    if ($answer[$index]) {
                        $aggregation[$option]++;
                    }
                }
            } elseif ($type === 'numeric') {
                // Aggregation logic for numeric type
                // Example: Calculate the sum of all answers
                if (!isset($aggregation['sum'])) {
                    $aggregation['sum'] = 0;
                }

                $aggregation['sum'] += $answer;
            }
        }
    }
     public function getSurveyList(Request $request)
    {
        $searchQuery = $request->query('search');

        $dataPath = storage_path('app/data');
        $files = File::allFiles($dataPath);

        $surveys = [];

        foreach ($files as $file) {
            $jsonData = File::get($file);
            $surveyData = json_decode($jsonData, true);
            // return $surveyData;

            $name = $surveyData['survey']['name'];
            $code = $surveyData['survey']['code'];

            // Filter by name or code if they match the search query
            if (!$searchQuery ||
                (strpos(strtolower($name), strtolower($searchQuery)) !== false) ||
                (strpos(strtolower($code), strtolower($searchQuery)) !== false)
            ) {
                $surveys[] = $surveyData;
            }
        }

        // Display the filtered survey list using your preferred data visualization method
        // Example: return a view with the filtered survey list data
        return view('survey.list', ['surveys' => $surveys, 'searchQuery' => $searchQuery]);
    }
    public function getAggregatedData($code)
    {
        $endpoint = "http://localhost:8000/api/{$code}.json";
        $response = Http::get($endpoint); // Make a GET request to the API endpoint
        $aggregatedData = $response->json();

        // Display the aggregated data using your preferred data visualization method
        // Example: return a view with the aggregated data
        return view('survey.aggregated_data', ['data' => $aggregatedData]);
    }
}
