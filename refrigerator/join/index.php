<?php
require_once '../dbconnect.php';
require_once '../common/commn/function.php';
session_start();

// Noticeエラーを消すためのコード。これをやらないとページが飛ばない
$code   = (string)filter_input(INPUT_POST, 'code');
$name   = (string)filter_input(INPUT_POST, 'name');
$pass   = (string)filter_input(INPUT_POST, 'pass');
$pass2  = (string)filter_input(INPUT_POST, 'pass2');
// $action = (string)filter_input(INPUT_POST, 'action');
$action = $_GET['action'];

$error = [
  'code'  => NULL,
  'name'  => NULL,
  'pass'  => NULL,
  'pass2' => NULL
];

if(!empty($_POST)){
  // エラー項目の確認
  if($code === ''){
    $error['code'] = 'blank';
  }
  if($name === ''){
    $error['name'] = 'blank';
  }
  if($_POST['pass'] === ''){
    $error['pass'] = 'blank';
  }
  if(strlen($_POST['pass']) < 4){
    $error['pass'] = 'length';
  }
  if($_POST['pass'] !== $_POST['pass2']){
    $error['pass'] = 'check';
  }
  if(is_null($error['code']) && is_null($error['name']) && is_null($error['pass'])){
    $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE code=?');
    $member->execute(array($code));
    $record = $member->fetch();
    if($record['cnt'] > 0){
      $error['code'] = 'duplicate';
    }
  if(is_null($error['code']) && is_null($error['name']) && is_null($error['pass'])){
    $_SESSION['join'] = $_POST;
    header('Location: check.php');
    exit();
  }
  
  }
}

// 書き直し
if($action === 'rewrite'){
  $error['rewrite'] = true;
  $code  = $_SESSION['join']['code'];
  $name  = $_SESSION['join']['name'];
  $pass  = $_SESSION['join']['pass'];
  $pass2 = $_SESSION['join']['pass2'];
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
  <link rel="stylesheet" href="../common/css/join_index.css">
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
      <h2 class="main__header">新規追加画面</h2>
      <p class="main__text">次のフォームに必要事項をご記入ください。<br>ユーザーIDには希望する任意の英数字を、パスワードには半角英数字４文字以上入力してください</p>
      <form action="" method="post" enctype="multipart/form-data">
        <dl class="form__list">
          <dt>ユーザーID<span class="required">必須</span></dt>
          <dd>
            <input type="text" name="code" maxlength="100" value="<?php echo h($code); ?>">
<?php if($error['code'] === 'blank'): ?>
<p class="error"><i class="fas fa-asterisk"></i> 希望するIDを入力してください</p>
<?php endif; ?>
          </dd>
          <dt>名前<span class="required">必須</span></dt>
          <dd>
          <input type="text" name="name" maxlength="255" value="<?php echo h($name) ?>">
<?php if($error['name'] === 'blank'): ?>
<p class="error"><i class="fas fa-asterisk"></i> お名前を入力してください</p>
<?php endif; ?>
        </dd>
        <dt>パスワード<span class="required">必須</span></dt>
        <dd>
        <input type="password" name="pass" maxlength="20" value="<?php echo h($pass); ?>">
<?php if($error['pass'] === 'blank'): ?>
<p class="error"><i class="fas fa-asterisk"></i> パスワードを入力してください</p>
<?php endif; ?>
<?php if($error['pass'] === 'length'): ?>
<p class="error"><i class="fas fa-asterisk"></i> パスワードは４文字以上で入力してください</p>
<?php endif; ?>
<?php if($error['pass'] === 'check'): ?>
<p class="error"><i class="fas fa-asterisk"></i> パスワードが一致しません。<br>再入力してください</p>
<?php endif; ?>
        </dd>
        <dt>パスワード再入力<span class="required">必須</span></dt>
        <dd>
        <input type="password" name="pass2" maxlength="20" value="<?php echo h($pass2); ?>">
        </dd>
      </dl>

      <div><input type="submit" value="入力内容を確認する" class="btn btn-primary btn-lg btn-block"></div>
      </form>
    </div>
  </main><!-- /.main -->

  <footer class="footer">
  <div class="container"><small class="footer_copy text-center">&copy; 2018 </small></div>
  </footer><!-- /.footer -->
</body>
</html>