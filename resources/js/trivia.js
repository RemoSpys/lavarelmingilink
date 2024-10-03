let players = [];
let playerNames = [];
let currentPlayer = 0;
let playerCount = 1;
let playerScores = [0, 0, 0];
let usedQuestions = [];
let questions = []; // This will hold the questions fetched from the server

document.getElementById('playerCount').addEventListener('change', displayPlayerNameInputs);
document.getElementById('startGame').addEventListener('click', startGame);
document.getElementById('restartGame').addEventListener('click', restartGame);

// Display player name inputs based on selected player count
function displayPlayerNameInputs() {
    const count = parseInt(document.getElementById('playerCount').value);
    const playerNamesDiv = document.getElementById('playerNames');
    playerNamesDiv.innerHTML = `<label class="block text-xl">Player 1 Name:</label>
        <input type="text" id="playerName1" class="border-2 rounded p-2 text-black w-full max-w-xs mx-auto mb-4" placeholder="Enter Player 1 Name">`;

    for (let i = 2; i <= count; i++) {
        playerNamesDiv.innerHTML += `<label class="block text-xl">Player ${i} Name:</label>
            <input type="text" id="playerName${i}" class="border-2 rounded p-2 text-black w-full max-w-xs mx-auto mb-4" placeholder="Enter Player ${i} Name">`;
    }
}

// Start the game by collecting player names and fetching questions
function startGame() {
    playerCount = parseInt(document.getElementById('playerCount').value);
    players = [];
    playerNames = [];

    // Collect player names
    for (let i = 1; i <= playerCount; i++) {
        const playerName = document.getElementById(`playerName${i}`).value || `Player ${i}`;
        playerNames.push(playerName);
    }

    usedQuestions = [];
    playerScores = [0, 0, 0];
    document.getElementById('playerSetup').classList.add('hidden');
    document.getElementById('gameArea').classList.remove('hidden');
    currentPlayer = 0;
    document.getElementById('playerTurn').textContent = `${playerNames[currentPlayer]}'s Turn`;

    // Fetch questions from the server
    fetchQuestions();
}

// Fetch 5 random questions from the server
function fetchQuestions() {
    fetch('/api/fetch-questions') // Update with your Laravel route
        .then(response => response.json())
        .then(data => {
            questions = data; // Store the fetched questions
            loadQuestion();
        })
        .catch(error => console.error('Error fetching questions:', error));
}

// Load the question and display it
function loadQuestion() {
    if (usedQuestions.length >= questions.length) {
        showScoreboard(); // Show scoreboard if all questions used
        return;
    }

    let playerQuestion = questions[usedQuestions.length]; // Use the next question
    usedQuestions.push(playerQuestion); // Keep track of used questions
    displayQuestion(playerQuestion);
}

// Display the current question and options
function displayQuestion(questionObj) {
    document.getElementById('questionArea').textContent = questionObj.question;
    document.getElementById('answerOptions').innerHTML = '';

    questionObj.options.forEach((option, index) => {
        const btn = document.createElement('button');
        btn.classList.add('bg-blue-500', 'text-white', 'p-2', 'm-2', 'rounded', 'btn-option');
        btn.textContent = option;
        btn.addEventListener('click', () => handleAnswer(index, questionObj.answer));
        document.getElementById('answerOptions').appendChild(btn);
    });
}

// Handle the answer selection
function handleAnswer(selected, correct) {
    document.querySelectorAll('.btn-option').forEach((btn, index) => {
        if (index === correct) {
            btn.classList.add('correct');
        } else if (index === selected) {
            btn.classList.add('selected-incorrect');
        } else {
            btn.classList.add('incorrect');
        }
        btn.disabled = true; // Disable buttons after selection
    });

    if (selected === correct) {
        playerScores[currentPlayer]++;
    }
    currentPlayer++;
    if (currentPlayer >= playerCount) {
        currentPlayer = 0;
    }
    setTimeout(() => {
        loadQuestion(); // Load next question after delay
    }, 2000); // 2 second delay for next question
}

// Show the final scoreboard
function showScoreboard() {
    document.getElementById('gameArea').classList.add('hidden');
    document.getElementById('scoreboard').classList.remove('hidden');
    let scoreDisplay = '';
    for (let i = 0; i < playerCount; i++) {
        scoreDisplay += `${playerNames[i]}: ${playerScores[i]} points<br>`;
    }
    document.getElementById('finalScores').innerHTML = scoreDisplay;
}

// Restart the game
function restartGame() {
    document.getElementById('scoreboard').classList.add('hidden');
    document.getElementById('playerSetup').classList.remove('hidden');
    displayPlayerNameInputs();
}
