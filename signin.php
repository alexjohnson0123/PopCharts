<?php
require("connect-db.php");
require("db.php");
?>

<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  signIn($_POST['username'],$_POST['myPassword']);
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Upsorn Praphamontripong">
  <meta name="description" content="A music trivia web app, developed for UVA Database Systems">
  <meta name="keywords" content="CS 3250, Upsorn, Praphamontripong, Software Testing">
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <title>Pop Charts</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="popcharts.css">  
</head>

<body>  
<?php include("header.php"); ?>
  
<div class="container">
  <div class="row">
    <h1 class="mt-4 mb-3">Sign In</h1>
  </div>
  <form action="<?php $_SERVER['PHP_SELF']?>" method="post" onsubmit="return validateInput()">
  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" class="form-control" id="username" name="username">
  </div>
  <div class="mb-3">
    <label for="myPassword" class="form-label">Password</label>
    <input type="password" class="form-control" id="myPassword" name="myPassword">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>