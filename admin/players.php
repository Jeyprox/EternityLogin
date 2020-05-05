<?php
include "../database-login.php";

if (!isset($_SESSION["userid"])) {
  header("location: ../login.php");
} else {
  // Get User ID
  $userid = $_SESSION['userid'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
  $result = $statement->execute(array('userid' => $userid));
  $user = $statement->fetch();

  if ($user["role"] < 3) {
    header("location: ../account/home.php");
  } else {
    $players = $pdo->query("SELECT * FROM players")->fetchAll();
    $teams = $pdo->query("SELECT * FROM teams ORDER BY avg_sr DESC")->fetchAll();

    // Get Team Id
    $teamSelectionItem = $_POST["team-selection-submit"];
    $statement = $pdo->prepare("SELECT * FROM teams WHERE teamname = :teamName");
    $result = $statement->execute(array('teamName' => $teamSelectionItem));
    $selectedTeam = $statement->fetch();

    // Get Players from Team
    if (isset($teamSelectionItem)) {
      $statement = $pdo->prepare("SELECT * FROM players WHERE team = :teamSelection");
      $result = $statement->execute(array('teamSelection' => $selectedTeam["id"]));
      $players = $statement->fetchAll();
    }

    if (isset($_POST['submit-creation'])) {
      $error = false;
      $playername = $_POST["playername"];
      $firstname = $_POST["firstname"];
      $lastname = $_POST["lastname"];
      $discordtag = $_POST["discordtag"];
      $battletag = $_POST["battletag"];
      $sr = $_POST["sr"];
      $role = $_POST["role"];
      $team = $_POST["team"];

      $generalError;
      $playernameError;
      $discordtagError;
      $battletagError;

      //Check for unique Items
      if (!$error) {
        $statement = $pdo->prepare("SELECT * FROM players WHERE playername = :playername");
        $result = $statement->execute(array('playername' => $playername));
        $user = $statement->fetch();

        if ($user !== false) {
          $playernameError = 'Playername already registered';
          $error = true;
        }

        $statement = $pdo->prepare("SELECT * FROM players WHERE discordtag = :discordtag");
        $result = $statement->execute(array('discordtag' => $discordtag));
        $user = $statement->fetch();

        if ($user !== false) {
          $discordtagError = 'DiscordTag already registered';
          $error = true;
        }

        $statement = $pdo->prepare("SELECT * FROM players WHERE battletag = :battletag");
        $result = $statement->execute(array('battletag' => $battletag));
        $user = $statement->fetch();

        if ($user !== false) {
          $battletagError = 'BattleTag already registered';
          $error = true;
        }
      }

      if (!$error) {
        $statement = $pdo->prepare('INSERT INTO players (playername, firstname, lastname, discordtag, battletag, team, sr, main_role) VALUES (:playername, :firstname, :lastname, :discordtag, :battletag, :team, :sr, :main_role)');
        $result = $statement->execute(array('playername' => $playername, 'firstname' => $firstname, 'lastname' => $lastname, 'discordtag' => $discordtag, 'battletag' => $battletag, 'team' => $team, 'sr' => $sr, 'main_role' => $role));

        if ($result) {
          header("location: players.php");
        } else {
          $generalError = 'Error while creating player';
        }
      }
    }
  }
}

if (isset($_POST["submit-saves"])) {
  // Get Content from table and update SQL db
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/825b250593.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/players.css">
  <link rel="stylesheet" href="../css/player/team-select.css">
  <title>Player List - EternityEsports</title>
</head>

<body>
  <section id="player-table">
    <div class="table-controls">
      <div class="team-select-box">
        <div class="select-button">
          <div class="select-value">
            <span><?php echo isset($_POST["team-selection-submit"]) ? $_POST["team-selection-submit"] : 'Select a team' ?></span>
          </div>
          <div class="arrows">
            <i class="fas fa-chevron-up control-icon"></i>
            <i class="fas fa-chevron-down control-icon"></i>
          </div>
        </div>
        <form class="team-selection team-selection-hidden" action="players.php" method="post">
          <?php
          foreach ($teams as $team) {
          ?>
            <input type="submit" value="<?php echo $team['teamname'] ?>" name="team-selection-submit" class="team-selection-submit" data-team-color="<?php echo $team['color'] ?>" />
          <?php
          }
          ?>
        </form>
      </div>
      <div class="edit-mode control-item">
        <span>Edit Mode</span>
        <i class="fas fa-edit control-icon"></i>
      </div>
      <form class="save-changes control-item" value="save-changes" action="players.php" method="post">
        <input type="hidden" name="submit-saves" value="1">
        <input type="submit" name="save-changes-submit" class="save-changes-submit">
        <span>Save Changes</span>
        <i class="fas fa-save control-icon"></i>
      </form>
    </div>
    <div class="table-content">
      <table id="table">
        <thead class="table-header">
          <tr class="table-row">
            <th>Playername</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>DiscordTag</th>
            <th>BattleTag</th>
            <th>Team</th>
          </tr>
        </thead>
        <tbody class="table-body">
          <?php
          foreach ($players as $player) {
            $statement = $pdo->prepare("SELECT teamname FROM teams WHERE id = :teamId");
            $result = $statement->execute(array('teamId' => $player["team"]));
            $teamName = $statement->fetch();
          ?>
            <tr class="table-row">
              <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["playername"] ?>" /></td>
              <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["firstname"] ?>"></td>
              <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["lastname"] ?>"></td>
              <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["discordtag"] ?>"></td>
              <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["battletag"] ?>"></td>
              <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $teamName[0] ?>"></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </section>
  <script src="../js/jquery-3.4.1.min.js"></script>
  <script src="../js/players.js"></script>
</body>

</html>