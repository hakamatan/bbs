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
  $dc->SessionStart();

  /*****************************/
  //  ログアウト
  /*****************************/
  if(isset($_POST['logout']))
  {
    //データ破棄
    $dc->SessionDestroy();
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
      $item = array('admin_id'=>$admin_id_, 'admin_pass_word'=>$admin_pass_word_, 'comment_bk_color'=>$comment_bk_color, 'comment_viewbk_color'=>$comment_viewbk_color, 'limitpageline'=> $limitpageline);
      $dc->SetSession($item);
      
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
      $item = array('admin_id'=>$admin_id_, 'admin_pass_word'=>$admin_pass_word_, 'comment_bk_color'=>$comment_bk_color, 'comment_viewbk_color'=>$comment_viewbk_color, 'limitpageline'=> $limitpageline);
      $dc->SetSession($item);
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
    
    //DB更新
    $bk_color = 0 < strlen($comment_bk_color_) ? $comment_bk_color_ : $free_bk_color_;
    $viewbk_color = 0 < strlen($comment_viewbk_color_) ? $comment_viewbk_color_ : $free_viewbk_color_;
    $db->comment_bk_color = $bk_color;
    $db->comment_viewbk_color = $viewbk_color;
    $db->limitpageline = $limitpageline_;
    $db->EditAdminInfo($_SESSION['admin_id']);

    //セッション変数定義
    $item = array('comment_bk_color'=>$bk_color, 'comment_viewbk_color'=>$viewbk_color, 'limitpageline'=> $limitpageline_);
    $dc->SetSession($item);
    
    //表示
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
  if(!$dc->CheckLogin())
  {//ログイン画面
    $view->pagetitle = $view->pagetitlearray['adminlogin'];
    $view->contents = $view->htmlAdminCheck();
    echo $view->htmlView();
    return;
  }

  /*****************************/
  //  ログイン時の表示
  /*****************************/
  if($dc->CheckLogin())
  {//設定画面
    $session_data = $dc->GetSession();
    $view->admin_id = $session_data['admin_id'];
    $view->limitpageline = $session_data['limitpageline'];
    $view->comment_bk_color = $session_data['comment_bk_color'];
    $view->comment_viewbk_color = $session_data['comment_viewbk_color'];
    
    $view->pagetitle = $view->pagetitlearray['adminsetting'];
    $view->contents = $view->htmlAdminSetting();
    echo $view->htmlView();
    return;
  }

?>
