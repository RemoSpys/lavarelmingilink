<!-- resources/views/game.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trivia Game</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .correct {
            background-color: #38a169 !important; /* Green */
        }
        .incorrect {
            background-color: #e53e3e !important; /* Red */
        }
        .selected-incorrect {
            background-color: #3182ce !important; /* Blue */
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center w-full max-w-md">
        <h1 class="text-4xl font-bold mb-4">Trivia Game</h1>

        <div class="mb-6">
            <h2 class="text-2xl mb-2" id="playerTurn"></h2>
            <h2 class="text-2xl mb-2" id="questionText">{{ $question->question }}</h2>
            <div id="answerOptions" class="space-y-2">
                <button class="answer-btn bg-blue-500 text-white py-2 px-4 rounded w-full" onclick="checkAnswer(1)">{{ $question->answer_1 }}</button>
                <button class="answer-btn bg-blue-500 text-white py-2 px-4 rounded w-full" onclick="checkAnswer(2)">{{ $question->answer_2 }}</button>
                <button class="answer-btn bg-blue-500 text-white py-2 px-4 rounded w-full" onclick="checkAnswer(3)">{{ $question->answer_3 }}</button>
            </div>
        </div>

        <div id="result" class="mt-4"></div>
    </div>

    <script>
        let correctAnswer = {{ $question->correct_answer }};
        let playerNames = JSON.parse(sessionStorage.getItem('playerNames'));
        let playerCount = parseInt(sessionStorage.getItem('playerCount'));
        let currentPlayerIndex = 0;
        let scores = new Array(playerCount).fill(0);

        function updatePlayerTurn() {
            document.getElementById('playerTurn').innerText = `${playerNames[currentPlayerIndex]}'s Turn`;
        }

        function checkAnswer(selectedAnswer) {
            const buttons = document.querySelectorAll('.answer-btn');
            const result = document.getElementById('result');

            $.ajax({
                url: '/check-answer',
                type: 'POST',
                data: {
                    answer: selectedAnswer,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    result.innerHTML = ''; // Clear previous results

                    // Highlight buttons based on correctness
                    buttons.forEach((btn, index) => {
                        if (index + 1 === correctAnswer) {
                            btn.classList.add('correct'); // Correct answer
                        } else if (index + 1 === selectedAnswer) {
                            btn.classList.add('selected-incorrect'); // User's selection (wrong)
                        } else {
                            btn.classList.add('incorrect'); // Incorrect answers
                        }
                        btn.disabled = true; // Disable buttons after selection
                    });

                    // Display feedback
                    if (data.correct) {
                        scores[currentPlayerIndex]++;
                        result.innerHTML = '<p class="text-green-500">Correct!</p>';
                    } else {
                        result.innerHTML = `<p class="text-red-500">Wrong answer. The correct answer was: ${correctAnswer}</p>`;
                    }

                    // Move to the next question after a short delay
                    setTimeout(() => {
                        $.ajax({
                            url: '/next-question',
                            type: 'GET',
                            success: function(nextData) {
                                document.body.innerHTML = nextData; // Load next question
                                currentPlayerIndex = (currentPlayerIndex + 1) % playerCount; // Switch to the next player
                                updatePlayerTurn(); // Update the player turn display
                            }
                        });
                    }, 2000); // Wait for 2 seconds before showing the next question
                }
            });
        }

        // Set initial player turn
        updatePlayerTurn();
    </script>
</body>
</html>
