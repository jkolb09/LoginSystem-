<?php
if (isset($_POST['signup-submit'])) {

  require 'dbh.inc.php';

  
  $username = $_POST['uid'];
  $email = $_POST['mail'];
  $password = $_POST['pwd'];
  $passwordRepeat = $_POST['pwd-repeat'];

    missing parenthesis!)
    //this is to make it easier for the user to login if they had a typo in login field//
  if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
    header("Location: ../signup.php?error=emptyfields&uid=".$username."&mail=".$email);
    exit();
  }

  else if (!preg_match("/^[a-zA-Z0-9]*$/", $username) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../signup.php?error=invaliduidmail");
    exit();
  }
  
  else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("Location: ../signup.php?error=invaliduid&mail=".$email);
    exit();
  }
 
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../signup.php?error=invalidmail&uid=".$username);
    exit();
  }
  
  else if ($password !== $passwordRepeat) {
    header("Location: ../signup.php?error=passwordcheck&uid=".$username."&mail=".$email);
    exit();
  }
  else {

   

   .
    $sql = "SELECT uidUsers FROM users WHERE uidUsers=?;";
  
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
     
      header("Location: ../signup.php?error=sqlerror");
      exit();
    }
    else {
     
      mysqli_stmt_bind_param($stmt, "s", $username);
      
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
     
      $resultCount = mysqli_stmt_num_rows($stmt);
      
      mysqli_stmt_close($stmt);
     // This wil let the user know the useranme is already taken.
      if ($resultCount > 0) {
        header("Location: ../signup.php?error=usertaken&mail=".$email);
        exit();
      }
      else {
          
        $sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?, ?, ?);";
        
        $stmt = mysqli_stmt_init($conn);
       
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          // User will be sent back to login page .
          header("Location: ../signup.php?error=sqlerror");
          exit();
        }
        else {

         
          $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

          mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
         
          mysqli_stmt_execute($stmt);
          header("Location: ../signup.php?signup=success");
          exit();

        }
      }
    }
  }
  
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
else {
  // the user will be sent back if they inputed the wrong information.
  header("Location: ../signup.php");
  exit();
}
