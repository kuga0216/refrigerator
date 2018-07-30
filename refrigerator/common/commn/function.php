<?php

// htmlspecialcharsのショートカット
function h($value){
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// E_NOTICE以外のエラーが出るようにする
error_reporting(E_ALL & ~E_NOTICE);

// フォームの情報を無害化して、なおかつテキストフィールドで改行出来るように処理
function hbr($str) {
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}