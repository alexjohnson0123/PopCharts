<?php
require("connect-db.php");
require("db.php");
?>

<?php
session_start();

$chartDate = '2002-3-2';
$chartID = getChartID($chartDate);
$rankings = getRankings($chartID);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['songName'])) {
    score($_POST['songName'], $chartID);
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
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="popcharts.css">  
</head>

<body>  
  <?php include("header.php"); ?>

  <div class="container text-center">
    <div class="row g-3 mt-4 mb-2">
      <div class="col">
        <h2>Billboard top 100 chart for: <?php echo $chartDate ?></h2>
      </div>  
    </div>
    <div class="row">
      <form action="<?php $_SERVER['PHP_SELF']?>" method="post" onsubmit="return validateInput()">
        <div class="input-group m-auto mb-2" style="max-width:600px">
          <input type="text" class="form-control" id="songName" name="songName">
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>
      </form>
    </div>
    <div class="row">
      <p>
        
      </p>
    </div>
    <div class = "row">
      <div class = "col">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">Rank</th>
            <th scope="col">Song</th>
            <th scope="col">Artist</th>
            <th scope="col">Last Week</th>
            <th scope="col">Peak Rank</th>
            <th scope="col">Weeks on Chart</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rankings as $ranking): ?>
            <tr class="align-middle">
              <th scope="row"><?php echo $ranking['rank']; ?></th>
              <?php if (scored($ranking['songID'], $chartID)) { ?>
                <td><?php echo $ranking['songName']; ?></td>
              <?php } else { ?>
                <td>???</td>
              <?php } ?>
              <td><?php echo $ranking['artistName']; ?></td>
              <td><?php echo $ranking['lastWeek']; ?></td>
              <td><?php echo $ranking['peakRank']; ?></td>
              <td><?php echo $ranking['weeksOnTop']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
    </div>
  </div>
  <!---------------->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>
