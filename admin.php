<?php
/************************************************/
//  管理画面
/************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');


  $view = new ViewClass();
  $dc = new DataCheckClass();
  $db = new DBClass();

  //セッション
  session_cache_limiter('private, must-revalidate');
  session_start();

  /*****************************/
  //  ログアウト
  /*****************************/
  if(isset($_POST['logout']))
  {
    //データ破棄
    Logout();
  }

  /*****************************/
  //  管理者ＩＤ処理
  /*****************************/
  $admin_id = null;
  $admin_pass_word = null;
  $comment_bk_color = null;
  $comment_viewbk_color = null;
  $free_bk_color = null;
  $free_viewbk_color = null;

  if(isset($_POST['add']) || isset($_POST['check']))
  {
    $admin_id = $_POST['admin_id'];
    $admin_pass_word = $_POST['admin_pass_word'];
    
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

    /*****************************/
    //  管理者ＩＤ新規登録
    /*****************************/
    if(isset($_POST['add']))
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
      
      $db->AddAdminInfo();
      
      //追加したデータ読込み
      $db->admin_id = $admin_id;
      $db->admin_pass_word = $admin_pass_word;
      
      $dt = $db->GetAdminInfo($admin_id);
      foreach ($dt as $dr)
      {
        //print $pass_word.','.$dr['pass_word'].';<br>';
        $comment_bk_color = $dr['comment_bk_color'];
        $comment_viewbk_color = $dr['comment_viewbk_color'];
      }

      //セッション変数定義
      $_SESSION['admin_id'] = $admin_id;
      $_SESSION['admin_pass_word'] = $admin_pass_word;
      $_SESSION['comment_bk_color'] = $comment_bk_color;
      $_SESSION['comment_viewbk_color'] = $comment_viewbk_color;
      
      $view->pagetitle = $view->pagetitlearray['insert'];
      $view->msg = $view->msgarray['ok'];
      $view->urlfile = $view->urlarray['admin'];
      $view->button = $view->buttonarray[1];
      $view->contents = $view->htmlMessage();
      echo $view->htmlView();
      return;
      break;
    }
 
    /*****************************/
    //  管理者ＩＤ認証チェック
    /*****************************/
    if(isset($_POST['check']))
    {//認証チェック
      $view->msg = $dc->AdminCheck($admin_id, $admin_pass_word, 'check');
      if(strlen($view->msg) > 0)
      {//エラーあり
        $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
        $view->contents = $view->htmlErrMessage();
        echo $view->htmlView();
        return;
      }

      //管理者テーブル読込み
      $db->admin_id = $admin_id;
      $db->admin_pass_word = $admin_pass_word;
      
      $dt = $db->GetAdminInfo($admin_id);
      foreach ($dt as $dr)
      {
        //print $pass_word.','.$dr['pass_word'].';<br>';
        $comment_bk_color = $dr['comment_bk_color'];
        $comment_viewbk_color = $dr['comment_viewbk_color'];
      }

      //セッション変数定義
      $_SESSION['admin_id'] = $admin_id;
      $_SESSION['admin_pass_word'] = $admin_pass_word;
      $_SESSION['comment_bk_color'] = $comment_bk_color;
      $_SESSION['comment_viewbk_color'] = $comment_viewbk_color;
    }
  }

  /*****************************/
  //  管理者設定更新
  /*****************************/
  if(isset($_POST['setting']))
  {//カラー設定
    /*print sprintf ("管理者設定更新 admin_id=%s, admin_pass_word=%s, comment_bk_color=%s, comment_viewbk_color=%s <br>",
    $_SESSION['admin_id'],
    $_SESSION['admin_pass_word'],
    $_SESSION['comment_bk_color'],
    $_SESSION['comment_viewbk_color']);
    */
    $comment_bk_color = $_POST['comcolor'];
    $comment_viewbk_color = $_POST['viewcolor'];
    $free_bk_color = $_POST['free_comcolor'];
    $free_viewbk_color = $_POST['free_viewcolor'];
    print sprintf ("更新ボタンcomment_bk_color->%s, comment_viewbk_color->%s <br>",
    $_POST['comcolor'], $_POST['viewcolor'] 
    );
    
    $db->comment_bk_color = 0 < strlen($comment_bk_color) ? $comment_bk_color : $free_bk_color;
    $db->comment_viewbk_color = 0 < strlen($comment_viewbk_color) ? $comment_viewbk_color : $free_viewbk_color;
    $db->EditAdminInfo($_SESSION['admin_id']);

    //セッション変数定義
    $_SESSION['comment_bk_color'] = $db->comment_bk_color;
    $_SESSION['comment_viewbk_color'] = $db->comment_viewbk_color;
    
    $view->pagetitle = $view->pagetitlearray['update'];
    $view->msg = $view->msgarray['ok'];
    $view->urlfile = $view->urlarray['admin'];
    $view->button = $view->buttonarray[1];
    $view->contents = $view->htmlMessage();
    echo $view->htmlView();
    return;
    break;
  }

  /*****************************/
  //  ログアウト時の表示
  /*****************************/
  if(!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_pass_word']))
  {//ログイン画面
    $view->pagetitle = $view->pagetitlearray['adminlogin'];
    $view->contents = $view->htmlAdminCheck();
    echo $view->htmlView();
    return;
  }

  /*****************************/
  //  ログイン時の表示
  /*****************************/
  if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_pass_word']))
  {//設定画面
    $view->admin_id = $_SESSION['admin_id'];
    $view->pagetitle = $view->pagetitlearray['adminsetting'];
    $view->contents = $view->htmlAdminSetting($_SESSION['comment_bk_color'], $_SESSION['comment_viewbk_color']);
    echo $view->htmlView();
    return;
  }

/*****************************/
//  データ破棄
/*****************************/
function Logout()
{
  $_SESSION = array();
  session_destroy();
}
?>
