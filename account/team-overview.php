<?php

include "../database-login.php";

if (!isset($_SESSION['userid'])) {
  header("location: ../login.php");
} else {
  $selectedPage = "team-overview";
  // Get User ID
  $userid = $_SESSION['userid'];

  // Get User
  $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
  $result = $statement->execute(array('userid' => $userid));
  $user = $statement->fetch();

  // Get Player
  $statement = $pdo->prepare("SELECT * FROM players WHERE user_identifier = :userid");
  $result = $statement->execute(array('userid' => $userid));
  $player = $statement->fetch();

  // Get the teams
  $teams = $pdo->query("SELECT * FROM teams ORDER BY avg_sr DESC")->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/account.css">
  <link rel="stylesheet" href="../css/account/team-overview.css">
  <script src="https://kit.fontawesome.com/825b250593.js" crossorigin="anonymous"></script>
  <title>Team Overview</title>
</head>
<body>
  <?php include "sidebar.php" ?>
  <div class="settings-container">
    <div class="settings-title">
      <h1>Team Overview</h1>
      <p>See all the information about your team.</p>
    </div>
    <div class="settings-content">
      <ul class="team-list">
        <?php
        foreach ($teams as $team) {
          if($team["id"] == $player["team"] || $user["role"] == "Admin") {
        ?>
        <li class="team-item">
          <!-- TODO: Create Team pages -->
          <a href="../management/team/<?php echo $team["teamname"] ?>" class="team-link">
            <div class="team-icon">
              <img src="../img/team-logos/<?php echo $team["teamname"] ?>" alt="<?php echo $team["teamname"] ?>">
            </div>
            <div class="team-content">
              <h1 class="team-name" style="color:<?php echo $team["color"] ?>"><?php echo $team["teamname"] ?></h1>
              <p class="team-sr">Average Team SR: <?php echo $team["avg_sr"] ?></p>
            </div>
          </a>
        </li>
        <?php
          }
        }
        ?>
      </ul>
    </div>
  </div>
</body>
</html>