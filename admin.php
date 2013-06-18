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
  if(isset($_POST['btn_insert']) || isset($_POST['btn_check']))
  {
    $admin_id_ = $_POST['admin_id'];
    $admin_pass_word_ = $_POST['admin_pass_word'];
    
    /*****************************/
    //  データ入力チェック
    /*****************************/
//    print printf("(データ入力チェック) admin_id_' => %s, 'admin_pass_word_' => %s <br>",$admin_id_, $admin_pass_word_);

    $itemarray = array('admin_id' => $admin_id_, 'admin_pass_word' => $admin_pass_word_);
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
    if(isset($_POST['btn_insert']))
    {
      //新規登録チェック
      $view->msg = $dc->AdminCheck($admin_id_, $admin_pass_word_);
      if(strlen($view->msg) > 0)
      {//エラーあり
        $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
        $view->contents = $view->htmlErrMessage();
        echo $view->htmlView();
        return;
      }
      
      //管理者テーブル追加
      //print printf("(管理者ＩＤ新規登録) admin_id_' => %s, 'admin_pass_word_' => %s <br>",$admin_id_, $admin_pass_word_);
      $db->admin_id = $admin_id_;
      $db->admin_pass_word = $admin_pass_word_;
      
      $db->AddAdminInfo();
      
      //追加したデータ読込み
      $dt = $db->GetAdminInfo($admin_id_);
      $comment_bk_color = '';
      $comment_viewbk_color = '';
      $limitpageline = '';
      foreach ($dt as $dr)
      {
        $comment_bk_color = $dr['comment_bk_color'];
        $comment_viewbk_color = $dr['comment_viewbk_color'];
        $limitpageline = $dr['limitpageline'];
      }

      //セッション変数定義
      $_SESSION['admin_id'] = $admin_id_;
      $_SESSION['admin_pass_word'] = $admin_pass_word_;
      $_SESSION['comment_bk_color'] = $comment_bk_color;
      $_SESSION['comment_viewbk_color'] = $comment_viewbk_color;
      $_SESSION['limitpageline'] = $limitpageline;
      
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
    if(isset($_POST['btn_check']))
    {//認証チェック
      $view->msg = $dc->AdminCheck($admin_id_, $admin_pass_word_, 'check');
      if(strlen($view->msg) > 0)
      {//エラーあり
        $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
        $view->contents = $view->htmlErrMessage();
        echo $view->htmlView();
        return;
      }

      //管理者テーブル読込み
      $db->admin_id = $admin_id_;
      $db->admin_pass_word = $admin_pass_word_;
      
      $dt = $db->GetAdminInfo($admin_id_);
      foreach ($dt as $dr)
      {
        $comment_bk_color = $dr['comment_bk_color'];
        $comment_viewbk_color = $dr['comment_viewbk_color'];
        $limitpageline = $dr['limitpageline'];
      }

      //セッション変数定義
      $_SESSION['admin_id'] = $admin_id_;
      $_SESSION['admin_pass_word'] = $admin_pass_word_;
      $_SESSION['comment_bk_color'] = $comment_bk_color;
      $_SESSION['comment_viewbk_color'] = $comment_viewbk_color;
      $_SESSION['limitpageline'] = $limitpageline;
    }
  }

  /*****************************/
  //  管理者設定更新
  /*****************************/
  if(isset($_POST['btn_setting']))
  {//カラー設定
    $comment_bk_color_ = $_POST['comcolor'];
    $comment_viewbk_color_ = $_POST['viewcolor'];
    $free_bk_color_ = $_POST['free_comcolor'];
    $free_viewbk_color_ = $_POST['free_viewcolor'];
    $limitpageline_ = $_POST['limitpageline'];
    
    $db->comment_bk_color = 0 < strlen($comment_bk_color_) ? $comment_bk_color_ : $free_bk_color_;
    $db->comment_viewbk_color = 0 < strlen($comment_viewbk_color_) ? $comment_viewbk_color_ : $free_viewbk_color_;
    $db->limitpageline = $limitpageline_;
    $db->EditAdminInfo($_SESSION['admin_id']);

    //セッション変数定義
    $_SESSION['comment_bk_color'] = $db->comment_bk_color;
    $_SESSION['comment_viewbk_color'] = $db->comment_viewbk_color;
    $_SESSION['limitpageline'] = $db->limitpageline;
    
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
    $view->limitpageline = $_SESSION['limitpageline'];
    $view->comment_bk_color = $_SESSION['comment_bk_color'];
    $view->comment_viewbk_color = $_SESSION['comment_viewbk_color'];
    $view->contents = $view->htmlAdminSetting();
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
