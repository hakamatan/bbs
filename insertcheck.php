<?php
  /******************************/
  /*  入力確認画面  */
  /******************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

  $handlename = $_POST['handlename'];
  $title = $_POST['title'];
  $comment = $_POST['comment'];
  $pass_word = $_POST['pass_word'];

  if(isset($_POST['insertchk']))
  {
    $view = new ViewClass();
    //データチェック
    $check = new DataCheckClass();
    $msg= $check->InputDataCheck($handlename, $title, $comment, $pass_word);
    if(strlen($msg)>0)
    {//エラーあり
      //失敗画面表示
      $view->pagetitle = $view->htmlSpanRed('エラー：確認してください。');
      $view->msg = $view->htmlSpanRed($msg);
      $view->button = $view->htmlButtonType();
      $view->contents = $view->htmlMessage();
      echo $view->htmlView();
      return;
    }

    $view->handlename = $handlename;
    $view->title = $title;
    $view->comment = nl2br($comment);
    $view->pass_word = $pass_word;
    $view->contents = $view->htmlComentCheck();
    echo $view->htmlView();
    return;
/*
    try
    {
      //データ追加
      $db = new DBClass();  //DB Open
      $db->DBOpen();
      $db->title;
      $db->board_id;
      $db->contents;
      $db->handlename;
      $db->pass_word;
      $db->InsertComment();
      $db->DbClose(); //DB Close
    }
  	catch (PDOException $e)
  	{
      print('Error:'.$e->getMessage());
      die();
  	}
*/

  }

?>