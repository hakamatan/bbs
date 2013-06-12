<?php
/*************************************************/
//  初期画面
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');

  //セッション開始
  session_start();

  $contents = '';
  $view = new ViewClass();

  /*****************************/
  //  コメント入力部作成
  /*****************************/
  $view->urlfile = $view->urlarray['add'];
  $contents .= $view->htmlCommentNewInput();

  /*****************************/
  //タイトル一覧表示部作成
  /*****************************/
  $db = new DBClass();
  $dt =$db->GetTitleView();
  $body = '';
  foreach ($dt as $dr)
  {
    $view->board_id = $dr['board_id'];
    $view->title = $dr['title'] != $dr['subject'] ? $dr['subject'] : $dr['title'];
    $view->handlename = $dr['handlename'];
    $view->add_date = $dr['add_date'];
    $view->up_date = $dr['add_date'] != $dr['up_date'] ? $dr['up_date'] : '0000-00-00 00:00:00';
    $body .= $view->htmlTitleViewBody();
 	}
  $contents .= $view->htmlTitleView($body);

  /*****************************/
  //  表示
  /*****************************/
  $view->pagetitle = $view->pagetitlearray['add'];
  $view->contents = $contents;
  echo $view->htmlView();
  return;
?>
