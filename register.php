<?php
session_start();
include 'db.php';

if (isset($_POST['register'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
  mysqli_query($conn, $query);

  header('Location: login.html');
}
?>