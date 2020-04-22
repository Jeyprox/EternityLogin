<?php
include "database-login.php";

$errorMessage;
$showPage = true;

if (!isset($_SESSION['userid'])) {
  $showPage = false;
  echo '<p class="error">Please <a href="login.php">log in</a>.</p>';
} else {
  //Get User ID
  $userid = $_SESSION['userid'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
  $result = $statement->execute(array('userid' => $userid));
  $user = $statement->fetch();

  if (isset($_GET['login'])) {
    $passwort = $_POST['passwort'];

    //Verification of password
    if (password_verify($passwort, $user['passwort'])) {
      header('location: account/change-password.php');
    } else {
      $errorMessage = 'Invalid Password';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/form.css">
  <title>Log In - EternityEsports</title>
</head>

<body>
  <?php
  if ($showPage) {
  ?>

    <form action="?login=1" method="post">
      <h1>Welcome</h1>
      <p><?php echo $user['email'] ?></p>

      <p>To continue, please verify that it's you.</p>
      <input type="password" placeholder="Password" maxlength="128" name="passwort">
      <?php
      if (isset($errorMessage)) {
      ?>
        <div class="error">
          <i class="fas fa-exclamation-circle"></i>
          <p class="error-message"><?php echo $errorMessage ?></p>
        </div>
      <?php
      }
      ?>
      <div class="login-footer">
        <a href="forgotpassword.php" class="forgot-password">Forgot Password?</a>
        <input type="submit" value="Continue">
      </div>
    </form>

  <?php } ?>
</body>

</html>