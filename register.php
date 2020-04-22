<?php
include "database-login.php";

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

if (isset($_POST['submit-form'])) {
  $error = false;
  $firstname = $_POST['firstname'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $passwort = $_POST['passwort'];
  $passwort2 = $_POST['passwort2'];

  $generalError;
  $emailError;
  $passwordError;
  $repeatPasswordError;
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
  if (strlen($passwort) == 0) {
    $passwordError = 'Enter a password';
    $error = true;
  }
  if ($passwort != $passwort2) {
    $repeatPasswordError = 'The passwords need to match';
    $error = true;
  }

  //Check for unique E-Mail
  if (!$error) {
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $result = $statement->execute(array('email' => $email));
    $user = $statement->fetch();

    if ($user !== false) {
      $emailError = 'E-Mail already registered';
      $error = true;
    }
  }

  //Check for unique Username
  if (!$error) {
    $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $result = $statement->execute(array('username' => $username));
    $user = $statement->fetch();

    if ($user !== false) {
      $userError = 'Username already registered';
      $error = true;
    }
  }

  //If there are no errors with the data we can register the account
  if (!$error) {
    $registrationcode = random_string();
    $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

    $statement = $pdo->prepare('INSERT INTO temp_registration (email, registrationcode, registrationcode_time, username, firstname, passwort) VALUES (:email, :registrationcode, NOW(), :username, :firstname, :passwort)');
    $result = $statement->execute(array('email' => $email, 'registrationcode' => sha1($registrationcode), 'username' => $username, 'firstname' => $firstname, 'passwort' => $passwort_hash));

    if ($result) {
      $to = $email;
      $from = "jeyprox.eternity@gmail.com";

      $subject = "Account Verification for EternityEsports";

      $headers = "From: Eternity Info <" . $from . ">\r\n";
      $headers .= "MIME-Version: 1.0\r\n"; 
      $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

      $ip = "192.168.2.133/LoginTest";
      $url_registrationcode = 'http://' . $ip . '/register/verify-account.php?email=' . $email . '&code=' . $registrationcode;

      $text = '<html><body style="font-size: 15px; text-align: center; max-width: 800px;">';
      $text .= '<img src="LoginTest/img/Banner.png" alt="EternityBanner" width="800px" >';
      $text .= '<div>';
      $text .= '<p>Hello ' . $username . ',</p>';
      $text .= '<p>thank you for creating an account on EternityEsports. We\'re happy to have you :)</p>';
      $text .= '</div>';
      $text .= '<p>Please activate your account!';
      $text .= '<a href="' . $url_registrationcode . '" style="display: block; text-decoration: none; color: #ffae00;">Activate Account</a>';
      $text .= '<p style="font-size: 0.8rem">This link is only valid for 24 hours. In case your E-Mail program doesn\'t display this link as a Hyperlink please copy it into your Browser.</p>';
      $text .= '<p>Have fun! Your Eternity Team <3</p>';
      $text .= '<p style="font-size: 0.9rem">In case this wasn\'t you, just ignore this E-Mail.</p>';
      $text .= '</body></html>';
  
      mail($to, $subject, $text, $headers);

      header("location: register/verification-info.php");
    } else {
      $generalError = 'Error while registering';
    }
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="./css/style.css">
  <script src="https://kit.fontawesome.com/825b250593.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./css/register.css">
  <link rel="stylesheet" href="./css/form.css">
  <title>Registration - EternityEsports</title>
</head>

<body>
  <form action="register.php" method="post">
    <div class="content">
      <div class="registration-title">
        <h1>Registration</h1>
        <p>Create your EternityEsports Account.</p>
      </div>

      <div class="registration-input">
        <div class="input-username">
          <input type="text" placeholder="Username" maxlength="32" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>">
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
        <div class="input-firstname">
          <input type="text" placeholder="First Name" maxlength="32" name="firstname" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>">
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
        <div class="input-email">
          <input type="email" placeholder="E-Mail" maxlength="255" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
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
        <div class="input-password">
          <input class="password" type="password" placeholder="Password" maxlength="128" name="passwort" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>">
          <?php
          if (isset($passwordError)) {
          ?>
            <div class="error">
              <i class="fas fa-exclamation-circle"></i>
              <p class="error-message"><?php echo $passwordError ?></p>
            </div>
          <?php
          }
          ?>
        </div>
        <div class="input-repeat-password">
          <input class="password" type="password" placeholder="Repeat Password" maxlength="128" name="passwort2" value="<?php echo isset($_POST['password2']) ? $_POST['password2'] : '' ?>">
          <?php
          if (isset($repeatPasswordError)) {
          ?>
            <div class="error">
              <i class="fas fa-exclamation-circle"></i>
              <p class="error-message"><?php echo $repeatPasswordError ?></p>
            </div>
          <?php
          }
          ?>
        </div>
        <div class="input-password-visibility">
          <i class="input-password-icon fas fa-eye-slash"></i>
        </div>
        <p class="input-password-info">Use a minimum of 8 characters with a mix of letters, numbers and symbols</p>
        <?php
        if (isset($generalError)) {
        ?>
          <div class="error">
            <i class="fas fa-exclamation-circle"></i>
            <p class="error-message"><?php echo $generalError ?></p>
          </div>
        <?php
        }
        ?>
      </div>

      <div class="registration-footer">
        <a href="login.php">Log In instead</a>

        <input type="hidden" name="submit-form" value="1">
        <input type="submit" value="Submit">
      </div>
    </div>

    <div class="logo">
      <img src="./img/EternityLogo.png" alt="Logo">
    </div>
  </form>
  <script src="./js/jquery-3.4.1.min.js"></script>
  <script src="./js/register.js"></script>
</body>

</html>