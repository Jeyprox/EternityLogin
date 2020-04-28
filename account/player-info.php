<?php
  include "../database-login.php";

  if (!isset($_SESSION['userid'])) {
    header("location: ../login.php");
  } else {
    $selectedPage = "player-info";

    // Get User ID
    $userid = $_SESSION['userid'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
    $result = $statement->execute(array('userid' => $userid));
    $user = $statement->fetch();

    $statement = $pdo->prepare("SELECT * FROM players WHERE user_identifier = :userid");
    $result = $statement->execute(array('userid' => $userid));
    $player = $statement->fetch();

    if (isset($_POST['submit-form'])) {
      $discordTag = $_POST['discordtag'];
      $battleTag = $_POST['battletag'];
      $sr = $_POST['sr'];
  
      $successMessage;
      $errorMessages = array();
      if ($discordTag == null) {
        array_push($errorMessages, "Enter a DiscordTag");
        $error = true;
      }
      if ($battleTag == null) {
        array_push($errorMessages, "Enter a BattleTag");
        $error = true;
      }
      if ($sr == null) {
        array_push($errorMessages, "Enter your SR");
        $error = true;
      } else if(!ctype_digit($sr)) {
        array_push($errorMessages, "SR must be numerical");
        $error = true;
      }
  
      if (!$error) {
        // Check for unique DiscordTag
        if ($discordTag !== $player['discordtag']) {
          $statement = $pdo->prepare("SELECT * FROM players WHERE discordtag = :discordtag");
          $result = $statement->execute(array('discordtag' => $discordTag));
          $playerDiscordTag = $statement->fetch();
  
          if ($playerDiscordTag !== false) {
            array_push($errorMessages, "DiscordTag aleady registered");
            $error = true;
          }
        }
  
        // Check for unique BattleTag
        if ($battleTag !== $player['battletag']) {
          $statement = $pdo->prepare("SELECT * FROM players WHERE battletag = :battletag");
          $result = $statement->execute(array('battletag' => $battleTag));
          $playerBattleTag = $statement->fetch();
  
          if ($playerBattleTag !== false) {
            array_push($errorMessages, "BattleTag already registered");
            $error = true;
          }
        }
      }

      if (!$error) {
        $statement = $pdo->prepare('UPDATE players SET discordtag = :discordtag, battletag = :battletag, sr = :sr WHERE user_identifier = :userid');
        $result = $statement->execute(array('discordtag' => $discordTag, 'battletag' => $battleTag, 'sr' => $sr, 'userid' => $userid));
  
        if ($result) {
          $statement = $pdo->prepare("SELECT * FROM players WHERE user_identifier = :userid");
          $result = $statement->execute(array('userid' => $userid));
          $player = $statement->fetch();
  
          $error = false;
          $successMessage = 'Successfully changed player info';
        } else {
          array_push($errorMessages, "Error while saving");
        }
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
  <link rel="stylesheet" href="../css/account.css">
  <link rel="stylesheet" href="../css/account/info-form.css">
  <script src="https://kit.fontawesome.com/825b250593.js" crossorigin="anonymous"></script>
  <title>Player Info</title>
</head>
<body>
  <?php include "sidebar.php" ?>
  <div class="settings-container">
    <div class="settings-title">
      <h1>Player Info</h1>
      <p>Change your player information like SR and names.</p>
    </div>
    <form action="player-info.php" method="post" class="settings-content">
      <div class="settings-item">
        <p class="change-title">DiscordTag</p>
        <div class="change-input">
          <input type="text" maxlength="32" name="discordtag" value=<?php echo $player['discordtag'] ?>>
          <i class="fas fa-pen input-edit-icon"></i>
        </div>
      </div>
      <div class="settings-item">
        <p class="change-title">BattleTag</p>
        <div class="change-input">
          <input type="text" maxlength="32" name="battletag" value=<?php echo $player['battletag'] ?>>
          <i class="fas fa-pen input-edit-icon"></i>
        </div>
      </div>
      <div class="settings-item">
        <p class="change-title">SR</p>
        <div class="change-input">
          <input type="text" minlength="3" maxlength="4" name="sr" value=<?php echo $player['sr'] ?>>
          <i class="fas fa-pen input-edit-icon"></i>
        </div>
      </div>
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
  <script src="../js/jquery-3.4.1.min.js"></script>
  <script src="../js/account.js"></script>
</body>
</html>