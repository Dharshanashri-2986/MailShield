<?php
/*=========================================================
  Project : MailShield
  File    : db.php
  Purpose : Establish connection with MySQL database
=========================================================*/

// Database credentials
$host = "localhost";
$user = "root";
$password = "";
$database = "mailshield";

// Create MySQL connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn)
{
    die("Database Connection Failed : " . mysqli_connect_error());
}
?>