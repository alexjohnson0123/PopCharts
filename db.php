<?php
session_start();

function getAllUsers() {
    global $db;
    $query = "SELECT * FROM Users";
    $statement = $db->prepare($query);  // compile
    $statement->execute();
    $result = $statement->fetchAll();   // fetch()
    $statement->closeCursor();

    return $result;
}

function getChartID($chartDate) {
    global $db;
    $query = "SELECT chartID FROM Chart WHERE chartDate = :chartDate";
    $statement = $db->prepare($query);  // compile
    $statement->bindValue(':chartDate', $chartDate);
    $statement->execute();
    $result = $statement->fetch();   // fetch()
    $statement->closeCursor();

    $test = $result["chartID"];

    return $test;
}

function getRankings($chartID) {
    global $db;
    $query = "SELECT * FROM Ranking NATURAL JOIN Song NATURAL JOIN Artist NATURAL JOIN SongStats WHERE chartID = :chartID ORDER BY rank";
    $statement = $db->prepare($query);  // compile
    $statement->bindValue(':chartID', $chartID);
    $statement->execute();
    $result = $statement->fetchAll();   // fetch()
    $statement->closeCursor();

    return $result;
}

function getSongName($songID) {
    global $db;
    $query = "SELECT songName FROM Song WHERE songID = :songID";
    $statement = $db->prepare($query);  // compile
    $statement->bindValue(':songID', $songID);
    $statement->execute();
    $result = $statement->fetchAll();   // fetch()
    $statement->closeCursor();

    return $result;
}

function addUser($username, $myPassword) {
    global $db;
    $passhash = password_hash($myPassword, PASSWORD_DEFAULT);
    $Users = getUsers();
    $userid = count($Users);

    $query = "INSERT INTO `Users` (`userID`, `username`, `myPassword`) VALUES (:userid, :username, :myPassword);";
    
    $statement = $db->prepare($query);
    $statement->bindValue(':userid', $userid);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':myPassword', $passhash);
    
    $statement->execute();
    $statement->closeCursor();
}

function getUsers(){
  global $db;

  $query = "SELECT * FROM `Users`;";
  $statement = $db->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $statement->closeCursor();
  
  return $result;
}

function getUserID($username){
  global $db;

  $query = "SELECT * FROM `Users` WHERE username = :username;";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();
  
  $test = $result["userID"];

  return $test;
}

function signIn($username, $myPassword) {
  global $db;

  $query = "SELECT * FROM `Users` WHERE username = :username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  $statement->closeCursor();

  $passhash = $result['myPassword'];

  if(password_verify($myPassword, $passhash))  {
    $_SESSION['username'] = $username;
    header("Location: popcharts.php");
    die();
  }
}


function scored($songID, $chartID){
  global $db;

  $userID = getUserID($_SESSION['username']);

  $query = "SELECT * FROM Score WHERE chartID = :chartID AND songID = :songID AND userID = :userID;";
  $statement = $db->prepare($query);
  $statement->bindValue(':userID', $userID);
  $statement->bindValue(':songID', $songID);
  $statement->bindValue(':chartID', $chartID);
  $statement->execute();
  
  if($statement->rowCount() == 1){
    return true;
  } else {
    return false;
  }
}

function getSongIDs($songName){
  global $db;

  $query = "SELECT * FROM Song WHERE songName = :songName";
  $statement = $db->prepare($query);
  $statement->bindValue(':songName', $songName);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();

  return $result;
}


function score($songName, $chartID){
  global $db;

  $songIDs = getSongIDs($songName);
  $userID = getUserID($_SESSION['username']);

  foreach ($songIDs as $songID) {
    $query = "INSERT INTO Score (chartID, songID, userID) VALUES (:chartID, :songID, :userID);";
    $statement = $db->prepare($query);
    $statement->bindValue(':chartID', $chartID);
    $statement->bindValue(':songID', $songID);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
  }
}

function getFavoriteSong() {
  global $db;
  $userID = getUserID($_SESSION['username']);

  $query = "SELECT * FROM FavoriteSong NATURAL JOIN Song WHERE userID = :userID";
  $statement = $db->prepare($query);
  $statement->bindValue(':userID', $userID);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();

  $test = $result['songName'];

  return $test;
}

function totalScore() {
  global $db;
  $userID = getUserID($_SESSION['username']);  

  $query = "SELECT * FROM Score WHERE userID = :userID;";
  $statement = $db->prepare($query);
  $statement->bindValue(':userID', $userID);
  $statement->execute();
  $result = $statement->fetch();

  $count = count($result);
  return $count;
}

function getSongID($songName) {
  global $db;

  $query = "SELECT * FROM Song WHERE songName = :songName;";
  $statement = $db->prepare($query);
  $statement->bindValue(':songName', $songName);
  $statement->execute();
  $result = $statement->fetch();

  $songID = $result["songID"];
  return $songID;
}

function setFavoriteSong($songName) {
  global $db;
  $songID = getSongID($songName);
  $userID = getUserID($_SESSION['username']);  

  $query = "INSERT INTO `FavoriteSong` (`userID`, `songID`) VALUES (:userid, :songID);";
  
  $statement = $db->prepare($query);
  $statement->bindValue(':userid', $userID);
  $statement->bindValue(':songID', $songID);
  
  $statement->execute();
  $statement->closeCursor();
}

function setFavoriteArtist($artistName) {
  global $db;
  $artistID = getArtistID($artistName);
  $userID = getUserID($_SESSION['username']);  


  $query = "INSERT INTO `FavoriteArtist` (`userID`, `artistID`) VALUES (:userid, :artistID);";
  
  $statement = $db->prepare($query);
  $statement->bindValue(':userid', $userID);
  $statement->bindValue(':artistID', $artistID);
  
  $statement->execute();
  $statement->closeCursor();
}

function getFavoriteArtist() {
  global $db;
  $userID = getUserID($_SESSION['username']);

  $query = "SELECT * FROM FavoriteArtist NATURAL JOIN Artist WHERE userID = :userID";
  $statement = $db->prepare($query);
  $statement->bindValue(':userID', $userID);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();

  $test = $result['artistName'];

  return $test;
}

function getArtistID($artistName) {
  global $db;

  $query = "SELECT * FROM Artist WHERE artistName = :artistName;";
  $statement = $db->prepare($query);
  $statement->bindValue(':artistName', $artistName);
  $statement->execute();
  $result = $statement->fetch();

  $songID = $result["artistID"];
  return $songID;
}

?>