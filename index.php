<?php
require("connect-db.php");
require("db.php");
?>

<?php

$date = date('y-m-d');
$chartDate = getChart($date);

$rankings = getRankings($chartDate);
$songs = json_encode(getSongs());

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  score($date, $_POST['score']);
}

$json = json_encode($rankings);
$dateString = json_encode($chartDate);
$playername = "";
if(isset($_SESSION['username'])) {
  $playername = $_SESSION['username'];
}

echo  "<script>
        var rankings = $json;
        var chartDate = $dateString;
        var gameOver = false;
        var username = '$playername';
      </script>";

if (isset($_SESSION['username'])) {
  $scoreReturn = getScore($date);

  if (count($scoreReturn) > 0) {
    $score = $scoreReturn[0]['score'];
    echo "<script> 
        gameOver = true;
      </script>
      ";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Alex Johnson">
  <meta name="description" content="A music trivia web app, developed for UVA Database Systems">
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <title>Pop Charts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="popcharts.css">
</head>

<body>  
  <?php include("header.php"); ?>
  <div class="container text-center" id="container-main">
    <div class="row g-3 mt-4 mb-2">
      <h2>Billboard top 10 chart for <span id="dateText"></span></h2>
    </div>
    <?php if (!isset($_SESSION['username'])) { ?>
      <script>var signedIn = false;</script>               
      <p>Sign in or create an account to play!</p>
    <?php } else if (count($scoreReturn) <= 0) { ?>
      <script>var signedIn = true;</script>
      <form action="<?php $_SERVER['PHP_SELF']?>" method="post" id="scoreForm" onsubmit="return validateInput()" hidden>
        <input id="scoreInput" type="text" name="score">
      </form>    
      <div class="row" style="display:flex">
        <span id="scoreText"></span><span id="guessesLeftText"></span>
      </div>  
      <div class="row">
        <form id="songForm">
          <div class="input-group mb-3">
            <input type="text" id="songInput" class="form-control" placeholder="Guess a song name" list="songsList" autocomplete="off" required>
            <datalist id="songsList"></datalist>

            <script>
              
              let songs = <?php echo $songs ?>;
              const datalist = document.getElementById("songsList");
              songs.forEach((song) => {
                const option = document.createElement('option');
                option.value = song;
                datalist.appendChild(option);
              });

            </script>

            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
      <div class="row">
        <div class="col"><button id="giveUpButton" type="button" class="btn btn-outline-danger">Give Up?</button></div>
      </div>
    <?php  } else { ?>
      <script>var signedIn = true;</script>
      <h3>Game Over!</h3>
      <h4>Final Score: <?php echo $score ?></h4>
    <?php } ?>
    <div class="row">
      <span id="alert" style="display:none">Hidden alert text</span>
    </div>
    <div class = "row" id="table-row">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">Rank</th>
            <th scope="col">Song</th>
            <th scope="col">Artist</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody id="table">
        </tbody>
      </table>
    </div>
  </div>
  <!---------------->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="./popcharts.js"></script>
</body>
</html>
