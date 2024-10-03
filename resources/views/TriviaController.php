<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class TriviaController extends Controller
{
    public function fetchQuestions()
    {
        // Fetch 10 random questions using the Eloquent model
        $questions = Question::inRandomOrder()->take(10)->get();
        
        // Return questions as JSON response
        return response()->json($questions);
    }
}
