<?php
session_start();

function alert($msg) {
  echo "<script type='text/javascript'>alert('$msg');</script>";
}

function getRankings($chartDate) {
    global $db;
    $query = "SELECT * FROM Ranking WHERE chartDate = :chartDate ORDER BY songRank";
    $statement = $db->prepare($query);  // compile
    $statement->bindValue(':chartDate', $chartDate);
    $statement->execute();
    $result = $statement->fetchAll();   // fetch()
    $statement->closeCursor();

    return $result;
}

function addUser($username, $myPassword) {
    global $db;
    $passhash = password_hash($myPassword, PASSWORD_DEFAULT);

    $query = "INSERT INTO `Users` (`username`, `myPassword`) VALUES (:username, :myPassword);";
    
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':myPassword', $passhash);
   
    $unique = true;
    try {
      $statement->execute();
      $statement->closeCursor();
    } catch (Exception $e) { 
      $unique = false;
      alert("Username taken.");
    } 
    
    if ($unique) {
      $_SESSION['username'] = $username;
      header("Location: index.php");
      die();
    }
}

function signIn($username, $myPassword) {
  global $db;

  $query = "SELECT * FROM `Users` WHERE username = :username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  $statement->closeCursor();

  if (!$result) {
    alert("Incorrect username or password.");
  } else {

    $passhash = $result['myPassword'];

    if(password_verify($myPassword, $passhash))  {
      $_SESSION['username'] = $username;
      header("Location: index.php");
      die();
    } else {
      alert("Incorrect username or password.");
    }
  }
}

function score($puzzleDate, $score) {
  global $db;

  $query = "INSERT INTO Score (username, puzzleDate, score) VALUES (:username, :puzzleDate, :score);";
  $statement = $db->prepare($query);
  $statement->bindValue(':puzzleDate', $puzzleDate);
  $statement->bindValue(':score', $score);
  $statement->bindValue(':username', $_SESSION['username']);
  $statement->execute();

  $statement->closeCursor();
}

function getSongs() {
  global $db;

  $query = "SELECT songName FROM Song";
  $statement = $db->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll(PDO::FETCH_COLUMN);

  return $result;
}

function getScore($puzzleDate) {
  global $db;

  $query = "SELECT score FROM Score WHERE username = :username AND puzzleDate = :puzzleDate";

  $statement = $db->prepare($query);
  $statement->bindValue(':username', $_SESSION['username']);
  $statement->bindValue(':puzzleDate', $puzzleDate);
  $statement->execute();
  $result = $statement->fetchAll();

  return $result;
}

function getChart($date) {
  global $db;

  $query = "SELECT chartDate FROM Puzzle WHERE puzzleDate = :puzzleDate";

  $statement = $db->prepare($query);
  $statement->bindValue(':puzzleDate', $date);
  $statement->execute();

  $result = $statement->fetchAll();

  if (count($result) > 0) {
    return $result[0]['chartDate'];
  } else {
    $randQuery = "SELECT chartDate FROM Ranking ORDER BY RAND() LIMIT 1";
    $randStatement = $db->prepare($randQuery);
    $randStatement->execute();
    $randResult = $randStatement->fetch();
    
    $insertQuery = "INSERT INTO Puzzle (puzzleDate, chartDate) VALUES (:puzzleDate, :chartDate);";
    $insertStatement = $db->prepare($insertQuery);
    $insertStatement->bindValue(':puzzleDate', $date);
    $insertStatement->bindValue(':chartDate', $randResult['chartDate']);
    $insertStatement->execute();

    return $randResult['chartDate'];
  }
}

function totalScore() {
  global $db;

  $query = "SELECT SUM(score) FROM Score WHERE username = :username;";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $_SESSION['username']);
  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);

  return $result['SUM(score)'];
}

function maxScore() {
  global $db;

  $query = "SELECT MAX(score) FROM Score WHERE username = :username;";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $_SESSION['username']);
  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);

  return $result['MAX(score)'];
}

function setFavoriteSong($songName) {
  global $db;

  $query = "INSERT INTO `FavoriteSong` (`username`, `songName`) VALUES (:username, :songName) ON DUPLICATE KEY UPDATE songName=:songName;";
  
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $_SESSION['username']);
  $statement->bindValue(':songName', $songName);
  
  $statement->execute();
  $statement->closeCursor();
}

function setFavoriteArtist($artistName) {
  global $db;

  $query = "INSERT INTO `FavoriteArtist` (`username`, `artistName`) VALUES (:username, :artistName) ON DUPLICATE KEY UPDATE artistName=:artistName;";
  
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $_SESSION['username']);
  $statement->bindValue(':artistName', $artistName);
  
  $statement->execute();
  $statement->closeCursor();
}

function getFavoriteArtist() {
  global $db;

  $query = "SELECT artistName FROM favoriteArtist WHERE username = :username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $_SESSION['username']);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();

  if ($result) {
    return $result['artistName'];
  } else {
    return "None yet!";
  }
}

function getFavoriteSong() {
  global $db;

  $query = "SELECT songName FROM favoritesong WHERE username = :username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $_SESSION['username']);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();

  if ($result) {
    return $result['songName'];
  } else {
    return "None yet!";
  }
}

?>
