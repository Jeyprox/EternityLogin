<?php
include "database-login.php";

$showpage = true;

if (!isset($_SESSION['userid'])) {
  $showpage = false;
  echo '<p class="error">Please <a href="login.php">log in</a>.</p>';
} else {
  // Get User ID
  $userid = $_SESSION['userid'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
  $result = $statement->execute(array('userid' => $userid));
  $user = $statement->fetch();

  if (isset($_POST['submit-form'])) {
    $firstname = $_POST['firstname'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $successMessage;
    $generalError;
    $emailError;
    $userError;
    $nameError;
    if ($firstname == null) {
      $nameError = 'Enter a name';
      $error = true;
    }
    if ($username == null) {
      $userError = 'Enter a username';
      $error = true;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailError = 'Enter a valid E-Mail';
      $error = true;
    }

    // Check for unique E-Mail
    if (!$error) {
      if ($email !== $user['email']) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $userEmail = $statement->fetch();

        if ($userEmail !== false) {
          $emailError = 'E-Mail already registered';
          $error = true;
        }
      }
    }

    // Check for unique Username
    if (!$error) {
      if ($username !== $user['username']) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $result = $statement->execute(array('username' => $username));
        $userUsername = $statement->fetch();

        if ($userUsername !== false) {
          $userError = 'Username already registered';
          $error = true;
        }
      }
    }

    // If there are no errors with the data we update the personal info
    if (!$error) {
      $statement = $pdo->prepare('UPDATE users SET email = :email, firstname = :firstname, username = :username WHERE id = :userid');
      $result = $statement->execute(array('email' => $email, 'username' => $username, 'firstname' => $firstname, 'userid' => $userid));

      if ($result) {
        $error = false;
        $successMessage = 'Successfully changed personal info';
      } else {
        $generalError = 'Error while registering';
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/account.css">
  <script src="https://kit.fontawesome.com/825b250593.js" crossorigin="anonymous"></script>
  <title>My Account - EternityEsports</title>
</head>

<body>
  <?php
  if ($showpage) {
  ?>
    <div class="settings">
      <div class="sidebar">
        <a class="account-menu" href="./account/applications.php">Applications</a>
        <a class="logout" href="logout.php">Logout</a>
      </div>
      <div class="personal-info-container">
        <form action="account.php" method="post">
          <div class="change-content">
            <p class="change-title">Name</p>
            <input type="text" maxlength="32" name="firstname" value=<?php echo $user['firstname'] ?>>
            <?php
            if (isset($nameError)) {
            ?>
              <div class="error">
                <i class="fas fa-exclamation-circle"></i>
                <p class="error-message"><?php echo $nameError ?></p>
              </div>
            <?php
            }
            ?>
          </div>
          <div class="change-content">
            <p class="change-title">Username</p>
            <input type="text" maxlength="32" name="username" value=<?php echo $user['username'] ?>>
            <?php
            if (isset($userError)) {
            ?>
              <div class="error">
                <i class="fas fa-exclamation-circle"></i>
                <p class="error-message"><?php echo $userError ?></p>
              </div>
            <?php
            }
            ?>
          </div>
          <div class="change-content">
            <p class="change-title">E-Mail</p>
            <input type="email" maxlength="255" name="email" value=<?php echo $user['email'] ?>>
            <?php
            if (isset($emailError)) {
            ?>
              <div class="error">
                <i class="fas fa-exclamation-circle"></i>
                <p class="error-message"><?php echo $emailError ?></p>
              </div>
            <?php
            }
            ?>
          </div>

          <input type="hidden" name="submit-form" value="1">
          <input type="submit" value="Save Changes">
          <?php
          if (isset($successMessage)) {
          ?>
            <div class="error">
              <i class="fas fa-exclamation-circle"></i>
              <p class="error-message"><?php echo $successMessage ?></p>
            </div>
          <?php
          }
          ?>
        </form>
        <a href="./password-verification.php" class="change-personal-info">
          <div class="change-content">
            <p class="change-title">Password</p>
            <p class="change-value">••••••••</p>
          </div>
          <div class="change-arrow">
            <i class="fas fa-chevron-right"></i>
          </div>
        </a>
      </div>
    </div>
  <?php
  } else {
  }
  ?>
</body>

</html>