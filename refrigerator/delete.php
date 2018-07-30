<?php
require_once 'dbconnect.php';
require_once 'common/commn/function.php';
session_start();

if(isset($_SESSION['id'])){
  $id = $_REQUEST['id'];
  $statement = $db->prepare('SELECT * FROM records WHERE id=?');
  $statement->execute(array($id));
  $state = $statement->fetch();
  if($state['member_id'] === $_SESSION['id']){
    $del = $db->prepare('DELETE FROM records WHERE id=?');
    $del->execute(array($id));
  }
}

header('Location: index.php');
exit;
?>