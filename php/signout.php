<?php
session_start();
session_unset();
header("Location: php/index.php");
die();
?>
