<?php
require_once 'dbconnect.php';
require_once 'common/commn/function.php';
session_start();
$vege   = (string)filter_input(INPUT_POST, 'vege');
$number = (string)filter_input(INPUT_POST, 'number');
$memo   = (string)filter_input(INPUT_POST, 'memo');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
  // ログインしているかどうかチェック
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
} else {
  // ログインしてなければログインへ
  header('Location: login.php');
  exit();
}

// 名前と数が空欄でなければ物たちを記録する
if(!empty($_POST)){
  if($_POST['vege'] !== '' && $_POST['number'] !== ''){
    $veges = $db->prepare('INSERT INTO records SET member_id=?, vege_name=?, number=?, memo=?, created=NOW()');
    $veges->execute(array(
      $member['id'],
      $_POST['vege'],
      $_POST['number'],
      $_POST['memo']
    ));
    header('Location: index.php');
    exit;
  }
}

// 物たちを取得してページ管理
$page = $_GET['page'];
if($page === ''){
  $page = 1;
}
$page = max($page, 1);

$counts = $db->query('SELECT COUNT(*) AS cnt FROM records');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 10);
$page = min($page, $maxPage);
$start = ($page - 1) * 10;
$records = $db->prepare('SELECT m.name, r.* FROM members m, records r WHERE m.id=r.member_id ORDER BY r.created ASC LIMIT ?, 10');
$records->bindParam(1, $start, PDO::PARAM_INT);
$records->execute();

// 書き直し機能
$id = 1;
if($action === 'rewrite'){
  $error['rewrite'] = true;
  $name = $_SESSION['join']['name'];
  $email = $_SESSION['join']['email'];
  $password = $_SESSION['join']['password'];
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
  <link rel="stylesheet" href="common/css/index.css">
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
    <div class="col-md-3">
      <h2 class="main__header">野菜管理画面</h2>
      <p class="main__text">登録したい野菜を選び、個数を入力してください</p>
      <form action="index.php" method="post">
        <dl class="form__list">
        <dt class="reg__item">野菜の名前</dt>
        <dd class="reg__fields">
          <select name="vege">
<?php
$veges = array('','アスパラ','えのき茸','かぼちゃ','キャベツ','きゅうり','じゃがいも','大根','玉ねぎ','トマト','なす','人参','白菜','ピーマン','ブロッコリー','ほうれん草','もやし','レタス');

foreach ($veges as $value){
  if($vege === $value){
    echo '<option value="' . $value . '" selected>' . $value . '</option>';
  } else {
    echo '<option value="' . $value . '">' . $value . '</option>';
  }
}
?>
          </select>
          </dd>
        <dt class="reg__item">個数</dt>
        <dd class="reg__fields">
        <select name="number">
<?php
$numbers = array('','3分の1','半分','3分の2','1','2','3','4','5');

foreach ($numbers as $value){
  if($number === $value){
    echo '<option value="' . $value . '" selected>' . $value . '</option>';
  } else {
    echo '<option value="' . $value . '">' . $value . '</option>';
  }
}
?>
        </select>
        </dd>
        <dt class="reg__item">メモ</dt>
        <dd class="reg__fields">
          <input type="text" name="memo">
        </dd>
        </dl>
      <div class="touroku__button"><input type="submit" value="登録する" name="button" class="btn btn-primary btn-lg btn-block"></div>
      <p class="header__link"><a href="main.php"><i class="fas fa-arrow-alt-circle-left"></i>ホームに戻る</a></p>
      </form>
      </div>
      <div class="col-md-9">
      <h2 class="main__header">野菜一覧</h2>
<table>
  <tr class="main__list2">
    <th class="main__item2"></th>
    <th class="main__item2">野菜名</th>
    <th class="main__item2">個数</th>
    <th class="main__item2">メモ</th>
    <th class="main__item2">登録日</th>
    <th class="main__item2"></th>
  </tr>
</table>

<table>
<?php foreach($records as $vegees): ?>
<tr class="main__list">
<td class="main__item"><?= $id; ?></td>
<td class="main__item"><?php echo h($vegees['vege_name']); ?></td>

<td class="main__item2"><?php echo h($vegees['number']); ?></td>

<td class="main__item"><?php echo h($vegees['memo']); ?></td>

<td class="main__item"><?php echo h($vegees['created']); ?></td>
<td class="main__item">
<?php if($_SESSION['id'] === $vegees['member_id']): ?>
[<a href="delete.php?id=<?php echo h($vegees['id']); ?>" style="color:#F33;">削除</a>]

<?php endif; ?></td>
<?php $id++; ?>
</tr>
<?php endforeach; ?>
</table>
<ul class="paging">
<?php if($page > 1){ ?>
  <li><a href="index.php?page=<?php echo $page - 1; ?>"><?= $page - 1; ?>ページへ</a></li>
<?php } else { ?>
  <li><?= $page; ?></li>
<?php } ?>
<?php if($page < $maxPage){ ?>
  <li><a href="index.php?page=<?php echo $page + 1; ?>"><?= $page + 1; ?>ページへ</a></li>
<?php } else { ?>
  <li><?= $page + 1; ?>ページへ</li>
<?php } ?>
</ul>
      </div>
      </div>
    </div>
  </main><!-- /.main -->

  <footer class="footer">
  <div class="container"><small class="footer_copy text-center">&copy; 2018 </small></div>
  </footer><!-- /.footer -->
</body>
</html>