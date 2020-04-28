<?php
$sidebarLink = "sidebar-link-active";

$isPlayer = false;
$statement = $pdo->prepare("SELECT * FROM players WHERE user_idendifier = :userid");
$result = $statement->execute(array('userid' => $userid));
$playerCheck = $statement->fetch();

if($playerCheck !== false || $user["role"] == "Admin") {
  $isPlayer = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/account.css">
  <title>Document</title>
</head>
<body>
  <nav class="sidebar">
    <ul class="sidebar-nav">
      <li class="home sidebar-item">
        <a href="./home.php" class="sidebar-link <?php if($selectedPage == 'home') { echo $sidebarLink; } ?>">
          <i class="fas fa-user sidebar-icon"></i>
          <span class="sidebar-text">Home</span>
        </a>
      </li>
      <li class="personal-info sidebar-item">
        <a href="./personal-info.php" class="sidebar-link <?php if($selectedPage == "personal-info") { echo $sidebarLink; } ?>">
          <i class="fas fa-id-card sidebar-icon"></i>
          <span class="sidebar-text">Personal Info</span>
        </a>
      </li>
      <?php if($isPlayer) { ?>
      <li class="player-info sidebar-item">
        <a href="./player-info.php" class="sidebar-link <?php if($selectedPage == "player-info") { echo $sidebarLink; } ?>">
          <i class="fas fa-user-cog sidebar-icon"></i>
          <span class="sidebar-text">Player Info</span>
        </a>
      </li>
      <li class="team-overview sidebar-item">
        <a href="./team-overview.php" class="sidebar-link <?php if($selectedPage == "team-overview") { echo $sidebarLink; } ?>">
          <i class="fas fa-users sidebar-icon"></i>
          <span class="sidebar-text">Team Overview</span>
        </a>
      </li>
      <?php } ?>
      <li class="applications sidebar-item">
        <a href="./applications.php" class="sidebar-link <?php if($selectedPage == "applications") { echo $sidebarLink; } ?>">
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
</body>
</html>