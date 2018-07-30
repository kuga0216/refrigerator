<?php
require_once 'dbconnect.php';
require_once 'common/commn/function.php';
session_start();

if($_COOKIE['code'] != ''){
  $_POST['code'] = $_COOKIE['code'];
  $_POST['pass'] = $_COOKIE['pass'];
  $_POST['save'] = 'on';
}

if(!empty($_POST)){
  // ログインの処理
  if($_POST['code'] !== '' && $_POST['pass'] !== ''){
    $login = $db->prepare('SELECT * FROM members WHERE code=? AND password=?');
      $login->execute(array(
      $_POST['code'],
      hash('sha256', $_POST['pass'])
    ));
    $member = $login->fetch();

    if($member){
      // ログイン成功
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      // ログイン情報を記憶する
      if($_POST['save'] === 'on'){
        setcookie('code', $_POST['code'], time()+60*60*24*14);
        setcookie('pass', $_POST['pass'], time()+60*60*24*14);
      }
      header('Location: main.php');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } else{
    $error['login'] = 'blank';
  }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>野菜室管理ツール</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link rel="stylesheet" href="common/css/login.css">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

</head>
<body>
  <header class="header">
    <div class="container">
      <h1 class="header__logo">野菜室管理ツール</h1>
    </div>
  </header><!-- /.header -->

  <main class="main">
    <div class="container">
      <h2 class="main__header">ログイン</h2>
      <p class="main__text">登録IDとパスワードを記入してログインボタンを押してください。<br>登録がまだの方はこちらからどうぞ<i class="fas fa-arrow-down"></i></p>
      <p class="main__text"><a href="join/"><i class="fas fa-arrow-alt-circle-right"></i>新規登録</a>をする</p>
      <form action="" method="post">
      <dl class="form__list">
        <dt>ユーザID</dt>
        <dd><input type="text" name="code" maxlength="100" value="<?php echo h($_POST['code']); ?>">
        <?php if($error['login'] === 'blank'): ?>
<p class="error"><i class="fas fa-asterisk"></i>メールアドレスとパスワードをご記入ください</p>
<?php endif; ?>
<?php if($error['login'] === 'failed'): ?>
<p class="error"><i class="fas fa-asterisk"></i>ログインに失敗しました。正しくご記入ください</p>
<?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd><input type="password" name="pass" maxlength="20" value="<?php echo h($_POST['pass']); ?>">
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd class="login__check"><input type="checkbox" name="save" id="save" value="on"><label for="save">次回から自動的にログインをする</label>
        </dd>
      </dl>
      <div><input type="submit" value="ログインする" class="btn btn-primary btn-lg btn-block"></div>
      </form>
    </div>
  </main><!-- /.main -->

  <footer class="footer">
  <div class="container"><small class="footer_copy text-center">&copy; 2018 </small></div>
  </footer><!-- /.footer -->
</body>
</html>