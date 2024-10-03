<!-- resources/views/game_over.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Over</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center w-full max-w-md">
        <h1 class="text-4xl font-bold mb-4">Game Over!</h1>
        
        <h2 class="text-2xl mb-4">Leaderboard:</h2>
        <div id="leaderboard" class="mb-4">
            <!-- Leaderboard will be populated here -->
        </div>

        <h3 class="text-xl mb-4">Total Scores:</h3>
        <p class="text-2xl mb-4" id="totalScore"></p>
        
        <a href="/" class="bg-blue-500 text-white py-2 px-4 rounded">Play Again</a>
    </div>

    <script>
        // Get player names and scores from sessionStorage
        const playerNames = JSON.parse(sessionStorage.getItem('playerNames'));
        const playerScores = JSON.parse(sessionStorage.getItem('playerScores'));

        // Populate the leaderboard
        const leaderboard = document.getElementById('leaderboard');
        playerNames.forEach((name, index) => {
            const score = playerScores[index] || 0; // Default score to 0 if not set
            const playerScore = document.createElement('p');
            playerScore.textContent = `${name}: ${score} points`;
            leaderboard.appendChild(playerScore);
        });

        // Calculate and display total score
        const totalScore = playerScores.reduce((acc, score) => acc + (score || 0), 0);
        document.getElementById('totalScore').textContent = `${totalScore} points`;
    </script>
</body>
</html>
