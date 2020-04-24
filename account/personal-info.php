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
    $firstname = $_POST['firstname'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $successMessage;
    $errorMessages = array();
    $generalError;
    $emailError;
    $userError;
    $nameError;
    if ($firstname == null) {
      array_push($errorMessages, "Enter a name");
      $error = true;
    }
    if ($username == null) {
      array_push($errorMessages, "Enter a username");
      $error = true;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      array_push($errorMessages, "Enter a valid E-Mail");
      $error = true;
    }

    if (!$error) {
      // Check for unique Username
      if ($username !== $user['username']) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $result = $statement->execute(array('username' => $username));
        $userUsername = $statement->fetch();

        if ($userUsername !== false) {
          array_push($errorMessages, "Username aleady registered");
          $error = true;
        }
      }

      // Check for unique E-Mail
      if ($email !== $user['email']) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $userEmail = $statement->fetch();

        if ($userEmail !== false) {
          array_push($errorMessages, "E-Mail already registered");
          $error = true;
        }
      }
    }

    // If there are no errors with the data we update the personal info
    if (!$error) {
      $statement = $pdo->prepare('UPDATE users SET email = :email, firstname = :firstname, username = :username WHERE id = :userid');
      $result = $statement->execute(array('email' => $email, 'username' => $username, 'firstname' => $firstname, 'userid' => $userid));

      if ($result) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
        $result = $statement->execute(array('userid' => $userid));
        $user = $statement->fetch();

        $error = false;
        $successMessage = 'Successfully changed personal info';
      } else {
        array_push($errorMessages, "Error while saving");
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/account.css">
  <link rel="stylesheet" href="../css/account/personal-info.css">
  <script src="https://kit.fontawesome.com/825b250593.js" crossorigin="anonymous"></script>
  <title>My Account - EternityEsports</title>
</head>

<body>
  <div class="settings">
    <nav class="sidebar">
      <ul class="sidebar-nav">
        <li class="home sidebar-item">
          <a href="./home.php" class="sidebar-link">
            <i class="fas fa-user sidebar-icon"></i>
            <span class="sidebar-text">Home</span>
          </a>
        </li>
        <li class="personal-info sidebar-item">
          <a href="./personal-info.php" class="sidebar-link sidebar-link-active">
            <i class="fas fa-id-card sidebar-icon"></i>
            <span class="sidebar-text">Personal Info</span>
          </a>
        </li>
        <li class="player-info sidebar-item">
          <a href="./player-info.php" class="sidebar-link">
            <i class="fas fa-user-cog sidebar-icon"></i>
            <span class="sidebar-text">Player Info</span>
          </a>
        </li>
        <li class="team-overview sidebar-item">
          <a href="./team-overview.php" class="sidebar-link">
            <i class="fas fa-users sidebar-icon"></i>
            <span class="sidebar-text">Team Overview</span>
          </a>
        </li>
        <li class="applications sidebar-item">
          <a href="./applications.php" class="sidebar-link">
            <i class="fas fa-user-tie sidebar-icon"></i>
            <span class="sidebar-text">Applications</span>
          </a>
        </li>
        <li class="logout sidebar-item">
          <a href="../logout.php" class="sidebar-link">
            <i class="fas fa-sign-out-alt sidebar-icon"></i>
            <span class="sidebar-text">Logout</span>
          </a>
        </li>
      </ul>
    </nav>
    <div class="settings-container">
      <div class="settings-title">
        <h1>Personal Info</h1>
        <p>Manage all the information about you and customise your account to your liking.</p>
      </div>
      <form action="personal-info.php" method="post" class="settings-content">
        <div class="personal-info-item">
          <p class="change-title">Name</p>
          <div class="change-input">
            <input type="text" maxlength="32" name="firstname" value=<?php echo $user['firstname'] ?>>
            <i class="fas fa-pen input-edit-icon"></i>
          </div>
        </div>
        <div class="personal-info-item">
          <p class="change-title">Username</p>
          <div class="change-input">
            <input type="text" maxlength="32" name="username" value=<?php echo $user['username'] ?>>
            <i class="fas fa-pen input-edit-icon"></i>
          </div>
        </div>
        <div class="personal-info-item">
          <p class="change-title">E-Mail</p>
          <div class="change-input">
            <input type="email" maxlength="255" name="email" value=<?php echo $user['email'] ?>>
            <i class="fas fa-pen input-edit-icon"></i>
          </div>
        </div>
        <a href="../password-verification.php" class="personal-info-item">
          <p class="change-title">Password</p>
          <div class="change-input">
            <p>••••••••</p>
            <i class="fas fa-chevron-right input-edit-icon"></i>
          </div>
        </a>
        <input type="hidden" name="submit-form" value="1">
        <input type="submit" value="Save Changes" class="submit-input">
        <?php
        if (isset($successMessage)) {
        ?>
          <div class="success">
            <i class="fas fa-check-circle"></i>
            <p class="success-message"><?php echo $successMessage ?></p>
          </div>
        <?php
        } else if($error) {
          foreach ($errorMessages as $errorMessage) {
        ?>
          <div class="error">
            <i class="fas fa-exclamation-circle"></i>
            <p class="error-message"><?php echo $errorMessage ?></p>
          </div>
        <?php
          }
        }
        ?>
      </form>
    </div>
  </div>
  <script src="../js/jquery-3.4.1.min.js"></script>
  <script src="../js/account.js"></script>
</body>
</html>