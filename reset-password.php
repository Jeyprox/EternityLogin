<?php
include "database-login.php";

if (!isset($_GET['userid']) || !isset($_GET['code'])) {
  die("The reset code that was transfered with this Link is not valid!");
}

$userid = $_GET['userid'];
$code = $_GET['code'];

//Get user
$statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
$result = $statement->execute(array('userid' => $userid));
$user = $statement->fetch();

//Check for user and valid code
if ($user === null || $user['passwortcode'] === null) {
  die("Matching user could not be found!");
}

if ($user['passwortcode_time'] === null || strtotime($user['passwortcode_time']) < (time() - 24 * 3600)) {
  die("This code has already expired!");
}

// Check passwordcode
if (sha1($code) != $user['passwortcode']) {
  die("Invalid code. Please check the link that was sent to you.");
}

//Code correct. User can create a new password
if (isset($_GET['send'])) {
  $passwort = $_POST['passwort'];
  $passwort2 = $_POST['passwort2'];

  if ($passwort != $passwort2) {
    echo '<p class="error">The passwords need to match!</p>';
  } else { //Save password and delete Code
    $passworthash = password_hash($passwort, PASSWORD_DEFAULT);
    $statement = $pdo->prepare("UPDATE users SET passwort = :passworthash, passwortcode = NULL, passwortcode_time = NULL WHERE id = :userid");
    $result = $statement->execute(array('passworthash' => $passworthash, 'userid' => $userid));

    if ($result) {
      header("location: login.php");
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - EternityEsports</title>
</head>
<body>
  <h1>Reset Password</h1>
  <form action="?send=1&amp;userid=<?php echo htmlentities($userid); ?>&amp;code=<?php echo htmlentities($code); ?>" method="post">
    <input type="password" placeholder="New Password" name="passwort">
    <input type="password" placeholder="Repeat Password" name="passwort2">

    <input type="submit" value="Submit Password">
  </form>
</body>
</html>