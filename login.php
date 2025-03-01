<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $_SESSION['user'] = $email;
    header('Location: dashboard.php');
  } else {
    echo "Incorrect username or password!";
  }
}
?>