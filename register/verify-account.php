<?php
include "../database-login.php";

$error = false;
$errorMessage = array();

if (!isset($_GET['email']) || !isset($_GET['code'])) {
  $error = true;
  array_push($errorMessage, "The reset code that was transfered with this Link is not valid!");
}

if(!$error) {
  $email = $_GET['email'];
  $code = $_GET['code'];

  // Get user
  $statement = $pdo->prepare("SELECT * FROM temp_registration WHERE email = :email");
  $result = $statement->execute(array('email' => $email));
  $user = $statement->fetch();
  // Check for user and valid code
  if ($user === null || $user['registrationcode'] === null) {
    array_push($errorMessage, "Matching user could not be found!");
  }

  if ($user['registrationcode_time'] === null || strtotime($user['registrationcode_time']) < (time() - 24 * 3600)) {
    array_push($errorMessage, "This code has already expired!");
  }

  if (sha1($code) != $user['registrationcode']) {
    array_push($errorMessage, "Invalid code. Please check the link that was sent to you.");
  }
}

if(!$error) {
  $statement = $pdo->prepare('INSERT INTO users (email, passwort, username, firstname) VALUES (:email, :passwort, :username, :firstname)');
  $result = $statement->execute(array('email' => $email, 'passwort' => $user['passwort'], 'username' => $user['username'], 'firstname' => $user['firstname']));

  $statement = $pdo->prepare('DELETE FROM temp_registration WHERE email = :email');
  $result = $statement->execute(array('email' => $email));

  if($result) {
    header("location: ../login.php");
  } else {
    array_push($errorMessage, "Error while activating your account");
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Verification - EternityEsports</title>
</head>
<body>
  <h1>Account Verfication</h1>
  <?php
  if(!empty($errorMessage)) {
    foreach ($errorMessage as $errorMsg) {
      echo $errorMsg;
    }
  }
  ?>
</body>
</html>