<?php
  include "../database-login.php";

  if (!isset($_SESSION['userid'])) {
    header("location: ../login.php");
  } else {
    $selectedPage = "home";

    // Get User ID
    $userid = $_SESSION['userid'];
    $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
    $result = $statement->execute(array('userid' => $userid));
    $user = $statement->fetch();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/account.css">
  <link rel="stylesheet" href="../css/account/home.css">
  <script src="https://kit.fontawesome.com/825b250593.js" crossorigin="anonymous"></script>
  <title>Eternity Account</title>
</head>
<body>
  <?php include "sidebar.php" ?>
  <div class="settings-container">
    <div class="settings-title">
      <h1>Welcome, TODO</h1>
      <p>Manage your Eternity Account and personalise your information</p>
    </div>
    <div class="settings-content">
      <div class="settings-item">
        <div class="settings-item-body">
          <div class="settings-item-body-content">
            <h1>Account personalisation</h1>
            <p>Manage all the information about you and customise your account to your liking.</p>
          </div>
          <i class="fas fa-id-card settings-item-icon"></i>
        </div>
        <div class="settings-item-footer">
          <a href="./personal-info.php">Manage & personalise your account</a>
        </div>
      </div>
      <div class="settings-item">
        <div class="settings-item-body">
          <div class="settings-item-body-content">
            <h1>Player customisation</h1>
            <p>Change your player information like SR and names.</p>
          </div>
          <i class="fas fa-user-cog settings-item-icon"></i>
        </div>
        <div class="settings-item-footer">
          <a href="./player-info.php">Change your Eternity Player settings</a>
        </div>
      </div>
      <div class="settings-item">
        <div class="settings-item-body">
          <div class="settings-item-body-content">
            <h1>Team overview</h1>
            <p>See all the information about your team.</p>
          </div>
          <i class="fas fa-users settings-item-icon"></i>
        </div>
        <div class="settings-item-footer">
          <a href="./team-overview.php">Check on your teams</a>
        </div>
      </div>
      <div class="settings-item">
        <div class="settings-item-body">
          <div class="settings-item-body-content">
            <h1>Applications</h1>
            <p>See your applications for positions at Eternity Esports.</p>
          </div>
          <i class="fas fa-user-tie settings-item-icon"></i>
        </div>
        <div class="settings-item-footer">
          <a href="./applications.php">See your applications</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>