<?php
include "../database-login.php";

if (!isset($_SESSION['userid'])) {
  header("location: ../login.php");
} else {
  // Get User ID
  $userid = $_SESSION['userid'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
  $result = $statement->execute(array('userid' => $userid));
  $user = $statement->fetch();

  if (isset($_POST['submit-form'])) {
    $newpassword = $_POST['password'];
    $confirmpassword = $_POST['password2'];

    $error = false;
    $passwordError;

    if ($newpassword !== $confirmpassword) {
      $error = true;
      $passwordError = "Passwords need to match";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/form.css">
  <title>Change Password - Eternity Esports</title>
</head>

<body>
  <form action="change-password.php" method="post">
    <div class="form-title">
      <h1>Change Password</h1>
      <p>Request a password change as <?php echo $user['username'] ?></p>
    </div>
    <input type="password" placeholder="New Password" maxlength="128" minlength="8" name="passwort" />
    <input type="password" placeholder="Confirm Password" maxlength="128" minlength="8" name="passwort2" />

    <input type="hidden" name="submit-form" value="1">
    <input type="submit" value="Change Password">
  </form>
</body>

</html>