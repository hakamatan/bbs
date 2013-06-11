<?php
  /******************************/
  //  管理画面
  /******************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');

  //セッション開始
//  session_start();

  $admin_id = $_POST('admin_id');
  $admin_pass_word = $_POST('admin_pass_word');
/*
  if ($_SERVER['REQUEST_METHOD'] != "POST") {
    //POSTで渡されたとき
    $id = $_POST[id];
  }
  elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    //GETで渡されたとき
    $id = $_GET[id];
  }
*/
  $dc = new DataCheckClass();
  $db = new DBClass();


  if(isset($_GET('add')) || isset($_GET('check'))
  {
    //データ入力チェック
    $itemarray = array('admin_id' => $admin_id, 'admin_pass_word' => $admin_pass_word);
    $view->msg = $dc->InputDataCheck($itemarray);
    if(strlen($view->msg) > 0)
    {//エラーあり
      $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
      $view->contents = $view->htmlErrMessage();
      echo $view->htmlView();
      return;
    }
  }

  if(isset($_POST('add'))
  {//新規登録チェック
    $view->msg = $dc->AdminCheck($admin_id, $admin_pass_word);
    if(strlen($view->msg) > 0)
    {//エラーあり
      $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
      $view->contents = $view->htmlErrMessage();
      echo $view->htmlView();
      return;
    }
    //管理者テーブル追加
    $db->admin_id = $admin_id;
    $db->admin_pass_word = $admin_pass_word;
    
    $db->SetAdminInfo();
    
    $view->pagetitle = $view->pagetitlearray['insert'];
    $view->msg = $view->msgarray['ok'];
    $view->button = $view->htmlButtonType('admin');
    $view->contents = $view->htmlMessage();
    echo $view->htmlView();
    return;
    break;
  }

  if(isset($_POST('check'))
  {//認証チェック
    $view->msg = $dc->AdminCheck($admin_id, $admin_pass_word, 'check');
    if(strlen($view->msg) > 0)
    {//エラーあり
      $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
      $view->contents = $view->htmlErrMessage();
      echo $view->htmlView();
      return;
    }
  }

    //表示
    $view->pagetitle = $view->pagetitlearray['adminlogin'];
    $view->contents = $view->htmlAdminCheck;
    echo $view->htmlView();
    return;
?>
