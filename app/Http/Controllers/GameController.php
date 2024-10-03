<?php

namespace App\Http\Controllers;

use App\Models\Question; 
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        // Fetch 5 random questions from the database
        $questions = Question::inRandomOrder()->take(5)->get();

        // Store questions in session for state management
        session(['questions' => $questions, 'currentQuestionIndex' => 0, 'score' => 0]);

        // Return the game view with the first question
        return view('game', [
            'question' => $questions[0],
            'currentQuestionIndex' => 0,
            'totalQuestions' => count($questions)
        ]);
    }

    public function nextQuestion(Request $request)
    {
        $questions = session('questions');
        $currentQuestionIndex = session('currentQuestionIndex');

        // Increment index for the next question
        $currentQuestionIndex++;

        // Check if there are more questions
        if ($currentQuestionIndex < count($questions)) {
            session(['currentQuestionIndex' => $currentQuestionIndex]);
            return view('game', [
                'question' => $questions[$currentQuestionIndex],
                'currentQuestionIndex' => $currentQuestionIndex,
                'totalQuestions' => count($questions)
            ]);
        } else {
            // Game over, show score or message
            return view('game_over', [
                'score' => session('score'),
                'totalQuestions' => count($questions)
            ]);
        }
    }

    public function checkAnswer(Request $request)
    {
        $questions = session('questions');
        $currentQuestionIndex = session('currentQuestionIndex');
        $correctAnswer = $questions[$currentQuestionIndex]->correct_answer;

        // Update score
        if ($request->input('answer') == $correctAnswer) {
            session(['score' => session('score') + 1]);
            return response()->json(['correct' => true, 'correctAnswer' => $correctAnswer]);
        } else {
            return response()->json(['correct' => false, 'correctAnswer' => $correctAnswer]);
        }
    }
}
