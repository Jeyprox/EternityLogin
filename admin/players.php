<?php 
  include "../database-login.php";

  $players = $pdo->query("SELECT * FROM players")->fetchAll();
  $teams = $pdo->query("SELECT * FROM teams ORDER BY avg_sr DESC")->fetchAll();

  $teamSelectionItem = $_POST["team-selection-submit"];
  if(isset($teamSelectionItem)) {
    $statement = $pdo->prepare("SELECT * FROM players WHERE team = :teamSelection");
    $result = $statement->execute(array('teamSelection' => $teamSelectionItem));
    $players = $statement->fetchAll();
  }

  if(isset($_POST['submit-creation'])) {
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

    if(!$error) {
      $statement = $pdo->prepare('INSERT INTO players (playername, firstname, lastname, discordtag, battletag, team, sr, main_role) VALUES (:playername, :firstname, :lastname, :discordtag, :battletag, :team, :sr, :main_role)');
      $result = $statement->execute(array('playername' => $playername, 'firstname' => $firstname, 'lastname' => $lastname, 'discordtag' => $discordtag, 'battletag' => $battletag, 'team' => $team, 'sr' => $sr, 'main_role' => $role));
      
      if ($result) {
        header("location: players.php");
      } else {
        $generalError = 'Error while creating player';
      }
    }
  }

  if(isset($_POST["submit-saves"])) {
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
  <div class="overlay overlay-hidden"></div>
  <div class="create-player create-player-hidden">
    <form class="create-player-form" method="post">
      <div class="create-player-title">
        <h1>Create Player</h1>
        <p>Create a new Eternity Player</p>
      </div>
      <div class="create-player-input">
        <div class="input-container input-playername">
          <input type="text" required placeholder="Playername" name="playername">
        </div>
        <div class="input-container input-firstname">
          <input type="text" placeholder="Firstname" name="firstname">
        </div>
        <div class="input-container input-lastname">
          <input type="text" placeholder="Lastname" name="lastname">
        </div>
        <div class="input-container input-discordtag">
          <input type="text" required placeholder="DiscordTag" name="discordtag">
        </div>
        <div class="input-container input-battletag">
          <input type="text" required placeholder="BattleTag" name="battletag">
        </div>
        <div class="input-container input-sr">
          <input type="text" required placeholder="SR" name="sr">
        </div>
        <div class="input-container input-role">
          <input type="text" required placeholder="Role" name="role">
        </div>
        <div class="input-container input-team">
          <div class="player-team-button">
            <div class="player-team-value">
              <span><?php echo isset($_POST["player-team-submit"]) ? $_POST["player-team-submit"] : 'Select a team' ?></span>
            </div>
            <div class="arrows">
              <i class="fas fa-chevron-up control-icon"></i>
              <i class="fas fa-chevron-down control-icon"></i>
            </div>
          </div>
          <div class="player-team-selection player-team-selection-hidden">
            <?php
            foreach ($teams as $team) {
            ?>
            <div class="player-team-selection-container">
              <input type="radio" name="team" class="player-team-selection-radio" value="<?php echo $team["teamname"] ?>" />
              <div class="player-team-radio-check"></div>
              <span class="player-team-selection-label"><?php echo $team['teamname'] ?></span>
            </div>
            <?php
            }
            ?>
          </div>
        </div>
      </div>
      <div class="create-player-footer">
        <div class="cancel-creation">
          <span>Cancel</span>
        </div>

        <input type="hidden" name="submit-creation" value="1">
        <input type="submit" class="create-player-submit" value="Create Player">
      </div>
    </form>
  </div>
  <?php
    if($error) {
  ?>
  <div class="create-player-error-box">
    <?php
      if(isset($generalError)) {
    ?>
      <div class="error">
        <i class="fas fa-exclamation-circle"></i>
        <p class="error-message"><?php echo $generalError ?></p>
      </div>
    <?php
      }
    ?>
    <?php
      if(isset($playernameError)) {
    ?>
      <div class="error">
        <i class="fas fa-exclamation-circle"></i>
        <p class="error-message"><?php echo $playernameError ?></p>
      </div>
    <?php
      }
    ?>
    <?php
      if(isset($discordtagError)) {
    ?>
      <div class="error">
        <i class="fas fa-exclamation-circle"></i>
        <p class="error-message"><?php echo $discordtagError ?></p>
      </div>
    <?php
      }
    ?>
    <?php
      if(isset($battletagError)) {
    ?>
      <div class="error">
        <i class="fas fa-exclamation-circle"></i>
        <p class="error-message"><?php echo $battletagError ?></p>
      </div>
    <?php
      }
    ?>
  </div>
  <?php } ?>
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
          <input type="submit" value="<?php echo $team['teamname'] ?>" name="team-selection-submit"
            class="team-selection-submit" data-team-color="<?php echo $team['color'] ?>" />
          <?php
          }
          ?>
        </form>
      </div>
      <div class="player-create control-item">
          <span>Create Player</span>
          <i class="fas fa-plus control-icon"></i>
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
        foreach ($players as $player) { ?>
          <tr class="table-row">
            <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["playername"] ?>" /></td>
            <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["firstname"] ?>"></td>
            <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["lastname"] ?>"></td>
            <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["discordtag"] ?>"></td>
            <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["battletag"] ?>"></td>
            <td class="table-item-container"><input type="text" class="table-item" readonly="true" value="<?php echo $player["team"] ?>"></td>
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