<?php
$user = "alex";
$password = "Commodore64?";
$database = "popcharts";

try {
	$db = new PDO("mysql:host=localhost;dbname=$database", $user, $password);
}
catch (PDOException $e) {
	$error_message = $e->getMessage();
	echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
catch (Exception $e) {
	$error_message = $e->getMessage();
	echo "<p>Error message $error_message </p>";
}
