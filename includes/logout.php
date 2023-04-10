
<?php ob_start(); ?>
<?php
session_start();
$_SESSION["username"] = null;
$_SESSION["firstname"] = null;
$_SESSION["lastname"] = null;
$_SESSION["user_role"] = null;
if (!headers_sent()) {
  header("Location: " . "../index.php");
} else {
  die("Redirection failed. Go back to <a href='../index.php'>homepage</a>");
}
 ?>
