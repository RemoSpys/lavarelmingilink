<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Game</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center w-full max-w-md">
        <h1 class="text-4xl font-bold mb-4">Welcome to the Game!</h1>
        <p class="text-lg mb-4">This is a simple game setup using Laravel.</p>

        <div class="mb-4">
            <h2 class="text-2xl mb-2">Select Number of Players:</h2>
            <select id="playerCount" class="ml-4 border-2 p-2 rounded text-black">
                <option value="1">1 Player</option>
                <option value="2">2 Players</option>
            </select>

            <div id="playerNames" class="mt-4">
                <label class="block text-xl">Player 1 Name:</label>
                <input type="text" id="playerName1" class="border-2 rounded p-2 text-black w-full max-w-xs mx-auto mb-4" placeholder="Enter Player 1 Name">
            </div>

            <div id="player2NameInput" class="mt-4 hidden">
                <label class="block text-xl">Player 2 Name:</label>
                <input type="text" id="playerName2" class="border-2 rounded p-2 text-black w-full max-w-xs mx-auto mb-4" placeholder="Enter Player 2 Name">
            </div>
        </div>

        <div class="bottom-buttons">
            <button id="startGame" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded">Start Game</button>
        </div>
    </div>
    
    <script>
        document.getElementById('playerCount').addEventListener('change', function() {
            const count = parseInt(this.value);
            const player2NameInput = document.getElementById('player2NameInput');
            player2NameInput.style.display = count === 2 ? 'block' : 'none';
        });

        document.getElementById('startGame').addEventListener('click', startGame);

        function startGame() {
            const playerCount = parseInt(document.getElementById('playerCount').value);
            const playerNames = [];
            playerNames.push(document.getElementById('playerName1').value || 'Player 1');

            if (playerCount === 2) {
                playerNames.push(document.getElementById('playerName2').value || 'Player 2');
            }

            // Store player names in session or pass them to the game page
            sessionStorage.setItem('playerNames', JSON.stringify(playerNames));
            sessionStorage.setItem('playerCount', playerCount);
            
            // Redirect to game page
            window.location.href = '/game';
        }
    </script>
</body>
</html>
