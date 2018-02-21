<?php

require_once('auth.php');
require_once('validate.php');
require_once('functions.php');

$auth = new Auth();

// ステイタス変数の初期化
$status = '';

// サインアップボタンが押された場合
// When the signup button is pressed.
if (! empty($_POST['signup'])) {
    if (! empty($_POST['uname']) and ! empty($_POST['upassword'])) {
        // ユーザ名の入力を確認する
        // Check user name input.
        if (! preg_match('/^[0-9a-zA-Z]{2,32}$/', $_POST['uname'])) {
            $status = 'error_uname';
        // パスワードの入力を確認する
        // Check password input.
        } elseif (! preg_match('/^[0-9a-zA-Z]{4,32}$/', $_POST['upassword'])) {
            $status = 'error_upassword';
        // ユーザー名とパスワードを登録する
        // Register username and password.
        } elseif ($auth->register($_POST['uname'], $_POST['upassword'])) {
            $status = 'Signed up.';
        // ユーザー名が既に存在する
        // Username already exists.
        } else {
            $status = 'This username is already registered. ';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>Sign up</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <main>
    <div class="form-container">
      <section>
        <h1>Sign up</h1>
        <div><?= $status ?></div>
      </section>
      <hr>
      <form action="" method="post">
        <div><input type="text" name="uname"
          value="<?= h($_POST['uname']) ?>" placeholder="Name"></div>
<?php
if ($err['uname']) {
    echo h($err['uname']);
}
?>
        <div><input type="password" name="upassword"
          value="<?= h($_POST['upassword']) ?>" placeholder="Password"></div>
<?php
if ($err['upassword']) {
    echo h($err['upassword']);
}
?>
        <div class="text-align-right"><input type="submit" id="signup" name="signup" value="Sign up"></div>
      </form>
      <hr>
      <div class="text-align-center">have an account ! <a href="index.php"><b>Login</b></a></div>
    </div>
  </main>
</body>

</html>
