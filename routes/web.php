<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GameController;

/*
|-------------------------------------------------------------------------- 
| Web Routes 
|-------------------------------------------------------------------------- 
| 
| Here is where you can register web routes for your application. These 
| routes are loaded by the RouteServiceProvider and all of them will 
| be assigned to the "web" middleware group. Make something great! 
| 
*/

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Test database connection
Route::get('/test-connection', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection is successful!';
    } catch (\Exception $e) {
        return 'Database connection failed: ' . $e->getMessage();
    }
});

// Route for the trivia game
Route::get('/game', [GameController::class, 'index']);

// Route to check the answer
Route::post('/check-answer', [GameController::class, 'checkAnswer']);

// Route to get the next question
Route::get('/next-question', [GameController::class, 'nextQuestion']);
