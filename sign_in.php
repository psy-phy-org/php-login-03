<?php

require_once('auth.php');
require_once('validate.php');
require_once('functions.php');

$auth = new Auth();

// ステイタス変数の初期化
$status = '';

// セッションがセットされていたらログイン済み
// If a session is set up, you are logged in.
if ($auth->getUser()) {
    $status = 'loggedin';

// ログインボタンが押された場合
// When the login button is pressed.
} elseif (! empty($_POST['login'])) {
    // 入力したユーザー名とパスワードに一致するレコードを検索する
    // Search records matching the entered user name and password
    if (! empty($_POST['uname']) && ! empty($_POST['upassword'])) {
        if ($auth->login($_POST['uname'], $_POST['upassword'])) {
            $status = 'Login success.';
            header('Location: ./index.php');
            exit();
        } else {
            $status = 'Login failure.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>Sign in</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>

  <main>
    <div class="form-container">
      <section>
        <h1>Login</h1>
        <div><?= $status ?></div>
      </section>
      <hr>
      <form action="" method="post">
        <div><input type="text" name="uname" value="<?= h($_POST['uname']) ?>" placeholder="Name"></div>
<?php
if ($err['uname']) {
    echo h($err['uname']);
}
?>
        <input type="password" name="upassword" value="<?= h($_POST['upassword']) ?>" placeholder="Password">
<?php
if ($err['upassword']) {
    echo h($err['upassword']);
}
?>
        <div class="text-align-right"><input type="submit" id="login" name="login" value="Login"></div>
      </form>
      <hr>
      <div class="text-align-center">Don't have account yet ! <a href="sign_up.php"><b>Sign Up</b></a></div>
    </div>
  </main>

</body>

</html>
