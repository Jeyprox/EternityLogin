<?php
include "../database-login.php";
$teamName = $_GET["team"];

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
  header("location: ../login.php");
} else {
  $userid = $_SESSION['userid'];

  // Get User
  $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
  $result = $statement->execute(array('userid' => $userid));
  $user = $statement->fetch();

  // Get role permission level
  $permissionLevel = (int) $user["role"];

  // Check if user has at least player permission
  if ($permissionLevel > 0) {
    // Get Team
    $statement = $pdo->prepare("SELECT * FROM teams WHERE teamname = :teamname");
    $result = $statement->execute(array('teamname' => $teamName));
    $team = $statement->fetch();

    // Get Player
    $statement = $pdo->prepare("SELECT * FROM players WHERE user_identifier = :userid");
    $result = $statement->execute(array('userid' => $userid));
    $player = $statement->fetch();

    function hasPermission($permissionLevel, $playerTeamId, $teamId)
    {
      switch ($permissionLevel) {
        case 1:
          if ($playerTeamId == $teamId) {
            return true;
          }
          break;
        case 2:
          // TODO: Check if coach has access to team
          break;
        case 3:
          return true;
          break;
        default:
          return false;
      }
    }

    // Check if user has access to team
    if (!hasPermission($permissionLevel, $player["team"], $team["id"])) {
      header("location: ../../account/home.php");
    }
  } else {
    header("location: ../../account/home.php");
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eternity <?php echo $teamName ?></title>
</head>

<body>

</body>

</html>