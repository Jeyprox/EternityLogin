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

if (isset($_GET['login'])) {
  $email = $_POST['email'];
  $passwort = $_POST['passwort'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $result = $statement->execute(array('email' => $email));
  $user = $statement->fetch();

  //Verification of password
  if ($user !== false && password_verify($passwort, $user['passwort'])) {
    $_SESSION['userid'] = $user['id'];

    $identifier = random_string();
    $securitytoken = random_string();

    $insert = $pdo->prepare("INSERT INTO securitytokens (user_id, identifier, securitytoken) VALUES (:user_id, :identifier, :securitytoken)");
    $insert->execute(array('user_id' => $user['id'], 'identifier' => $identifier, 'securitytoken' => sha1($securitytoken)));
    setcookie("identifier", $identifier, time() + (3600 * 24 * 365)); //1 year valid
    setcookie("securitytoken", $securitytoken, time() + (3600 * 24 * 365));

    header('location: account/home.php');
  } else {
    $errorMessage = 'Invalid E-Mail or Password';
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/form.css">
  <link rel="stylesheet" href="./css/login.css">
  <script src="https://kit.fontawesome.com/825b250593.js" crossorigin="anonymous"></script>
  <title>Log In - EternityEsports</title>
</head>

<body>

  <form action="?login=1" method="post">
    <div class="content">
      <div class="login-title">
        <h1>Login</h1>
        <p>Log into your EternityEsports Account.</p>
      </div>

      <div class="login-input">
        <input type="email" placeholder="E-Mail" maxlength="255" name="email" require>
        <input type="password" placeholder="Password" maxlength="128" name="passwort" require>

        <?php
        if (isset($errorMessage)) {
        ?>
          <div class="error">
            <i class="fas fa-exclamation-circle"></i>
            <p class="error-message"><?php echo $errorMessage ?></p>
          </div>
        <?php
        }
        ?>
      </div>

      <div class="login-footer">
        <a href="register.php">Create Account</a>
        <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
      </div>

      <input type="submit" value="Continue">
    </div>
  </form>
</body>

</html>