<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=Eternity', 'eternity', 'EternityWebsite');

$showpage = true;

if (!isset($_SESSION['userid'])) {
  $showpage = false;
  echo '<p class="error">Please <a href="login.php">log in</a>.</p>';
} else {
  //Get User ID
  $userid = $_SESSION['userid'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
  $result = $statement->execute(array('userid' => $userid));
  $user = $statement->fetch();

  echo "Welcome " . $user['username'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>Change Name - EternityEsports</title>
</head>

<body>
  <?php
  if ($showpage) {
  ?>
    <input type="text" maxlength="32" name="firstname" value=<?php echo $user['firstname'] ?>>
  <?php
  } else {
  }
  ?>
</body>

</html>