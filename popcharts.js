const table = document.getElementById("table");
const form = document.getElementById("songForm");
const input = document.getElementById("songInput");
const scoreText = document.getElementById("scoreText");
const giveUpButton = document.getElementById("giveUpButton");
const guessesLeftText = document.getElementById("guessesLeftText");
const dateText = document.getElementById("dateText");
const maxHints = 2;

let guessed = [];
let hints = [];
let guesses = 10;

for (let i = 0; i < rankings.length; i++) {
  guessed.push(false);
  hints.push(0);
}

// Update the HTML to display the ranking data, as well as correctly guessed song titles and hints
function renderTable() {
  let tableHtml = "";

  for (let i = 0; i < rankings.length; i++){
    ranking = rankings[i];
    let songDisplay = "???";
    
    // Modify the string based on the number of hints given or if the song has been guessed
    if(guessed[i] || gameOver) {
      songDisplay = ranking.songName;
    } else if(hints[i] === 1) {
        songDisplay = ranking.songName.replace(/[a-zA-Z0-9]/g, "*");
    } else if (hints[i] >= 2) {
        songDisplay = ranking.songName.replace(/\B[a-zA-Z0-9]/g, "*");
    }

    // Format and add entries
    tableHtml += `
      <tr>
        <th>${ranking.rank}</th>
        <td>${songDisplay}</td>
        <td>${ranking.artistName}</td>
        <td>${ranking.lastWeek}</td>
        <td>${ranking.peakRank}</td>
        <td>${ranking.weeksOnTop}</td>
        <td><button id="hint-btn-${i}" onclick="getHint(${i})" type="button" class="hint btn btn-light">Hint?</button></td>
      </tr>`;
  }
  table.innerHTML = tableHtml;
  
  // Conditionally disable hint buttons
  const hintButtons = document.querySelectorAll(".hint");
  for (let i = 0; i < rankings.length; i++) {
    if (!signedIn || gameOver || hints[i] >= maxHints) {
      hintButtons[i].setAttribute("disabled", true);
    }
  }

  let date = chartDate.split('-');
  dateText.innerText = date[1] + "/" + date[2] + "/" + date[0];

  if (signedIn && !gameOver) {
    scoreText.innerText = "Score: " + getScore();
    guessesLeftText.innerText = "Guesses Left: " + guesses;
  }
}

// Get a hint for a certain song title
function getHint(id) {
  if (guessed[id]){
    console.log("already guessed this song")
  } else if (hints[id] < maxHints) {
    hints[id]++;
  } else {
    console.log("max number of hints reached");
  }
  renderTable();
}

// Count the number of correctly guessed songs, subtracting points for hints
function getScore() {
  let score = 0;
  for(let i = 0; i < guessed.length; i++) {
    if(guessed[i]) {
      score += 5*(4 - hints[i]);
    }
  }
  return score;
}

// submit a guess for a song title
function makeGuess(e) {
  e.preventDefault();
  let guess = input.value;
  
  for (let i = 0; i < rankings.length; i++) {
    if (guess.toLowerCase() === rankings[i].songName.toLowerCase()) {
      guessed[i] = true;
      console.log("correct guess");
      break;
    }
  }

  input.value = "";

  guesses--;
  if (guesses <= 0) {
    giveUp();
  } else {
    renderTable();
  }
}

// give up on the puzzle and submit final score
function giveUp() {
  console.log("gave up");
  gameOver = true;
  
  document.getElementById("scoreInput").value = getScore();
  document.getElementById("scoreForm").submit();
}

renderTable();
if (signedIn && !gameOver) {
  form.addEventListener("submit", makeGuess);
  giveUpButton.addEventListener("click", giveUp);
}