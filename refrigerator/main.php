<?php
require_once 'dbconnect.php';
require_once 'common/commn/function.php';
session_start();

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
  // ログインしている
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array(
    $_SESSION['id']
  ));
  $member = $members->fetch();
} else {
  // ログインしてない
  header('Location: login.php');
  exit();
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
  <link rel="stylesheet" href="common/css/main.css">
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
    <div class="row">
    <div class="col-md-12">
      <h2 class="main__header">HOW TO USE!</h2>
      <p class="main__text">ようこそ <?php echo $member['name']; ?> さん</p>
      <p class="main__text"><i class="fas fa-star"></i>このツールは野菜室のなかの野菜達を管理するツールです！</p>
      <p class="main__text"><i class="fas fa-star"></i><a href="index.php
      ">こちら<i class="fas fa-arrow-alt-circle-left"></i></a>のページに進んで頂き、野菜名、個数を入力してください！</p>
      <p class="main__text"><i class="fas fa-star"></i>メモは何か書きたいことがあれば記入してください！</p>
      <p class="main__text2"><a href="index.php">野菜室管理</a></p>
      <p class="main__text">ログアウトはこちらから</p>
      <p class="main__link"><a href="logout.php">ログアウト</a></p＞
      </div>
      </div>
    </div>
  </main><!-- /.main -->

  <footer class="footer">
  <div class="container"><small class="footer_copy text-center">&copy; 2018 </small></div>
  </footer><!-- /.footer -->
</body>
</html>