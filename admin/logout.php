<?php 
include("header.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["logout"])) {
  logout();
}
?>