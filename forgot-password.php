<?php
$pdo = new PDO('mysql:host=localhost;dbname=Eternity', 'eternity', 'EternityWebsite');

function random_string()
{
  if (function_exists('random_bytes')) {
    $bytes = random_bytes(16);
    $str = bin2hex($bytes);
  } else if (function_exists('openssl_random_pseudo_bytes')) {
    $bytes = openssl_random_pseudo_bytes(16);
    $str = bin2hex($bytes);
  } else {
    $str = md5(uniqid('aifmcRdbTIGOSiek', true));
  }
  return $str;
}


$showForm = true;

if (isset($_GET['send'])) {
  if (!isset($_POST['email']) || empty($_POST['email'])) {
    $error = '<p class="error">Please enter a valid E-Mail</p>';
  } else {
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $result = $statement->execute(array('email' => $_POST['email']));
    $user = $statement->fetch();

    if ($user === false) {
      $error = '<p class="error">No user found</p>';
    } else {
      //Check if user has a valid Passwordcode
      $passwortcode = random_string();
      $statement = $pdo->prepare("UPDATE users SET passwortcode = :passwortcode, passwortcode_time = NOW() WHERE id = :userid");
      $result = $statement->execute(array('passwortcode' => sha1($passwortcode), 'userid' => $user['id']));

      $recipient = $user['email'];
      $subject = "New Password for EternityEsports";
      $from = "From: Eternity Info <jeyprox.eternity@gmail.com>";
      $url_passwortcode = 'http://192.168.2.133/LoginTest/reset-password.php?userid=' . $user['id'] . '&code=' . $passwortcode;
      $text = 'Hello ' . $user['username'] . ', you requested a new password on EternityEsports.
      To change your password, please go to ' . $url_passwortcode . ' in the next 24 hours.
      In case this wasn\'t you please ignore this E-Mail.
 
      Your Eternity Team';

      mail($recipient, $subject, $text, $from);

      // Header to Homepage
      echo "A Link was sent to your E-Mail adress to change your password. Please use this link in the next 24 hours.";
      $showForm = false;
    }
  }
}

if ($showForm) :
?>

  <h1>Forgot Password</h1>
  <p>Enter your E-Mail to request a new password!</p>

  <?php
  if (isset($error) && !empty($error)) {
    echo $error;
  }
  ?>

  <form action="?send=1" method="post">
    <input type="email" placeholder="E-Mail" name="email" value="<?php echo isset($_POST['email']) ? htmlentities($_POST['email']) : ''; ?>">
    <input type="submit" value="Get Password">
  </form>

<?php
endif; //Endif von if($showForm)
?>