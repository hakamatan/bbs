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
    header('Location: '.$vew->urlarray['admin']); 
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
      /*****************************/
      //  新規登録チェック
      /*****************************/
      $view->msg = $dc->AdminCheck($admin_id_, $admin_pass_word_);
      if(strlen($view->msg) > 0)
      {//エラーあり
        $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
        $view->contents = $view->htmlErrMessage();
        echo $view->htmlView();
        return;
      }
      
      /*****************************/
      //  DB追加
      /*****************************/
      $db->admin_id = $admin_id_;
      $db->admin_pass_word = $admin_pass_word_;
      $db->AddAdminInfo();
      
      /*****************************/
      //  セッション変数設定
      /*****************************/
      $dc->SetSession($dc->SetDataToSessionItem($db->GetAdminInfo($admin_id_)));
      $dc->SetCookie($admin_id_);
      
      /*****************************/
      //  追加確認画面表示
      /*****************************/
      $view->pagetitle = $view->pagetitlearray['insert'];
      $view->msg = $view->msgarray['ok'];
      $view->urlfile = $view->urlarray['admin'];
      $view->button = $view->buttonarray[1];
      $view->contents = $view->htmlMessage();
      echo $view->htmlView();
      return;
    }
 
    /*****************************/
    //  管理者ＩＤ認証チェック
    /*****************************/
    if(isset($_POST['btn_check']))
    {
      $view->msg = $dc->AdminCheck($admin_id_, $admin_pass_word_, 'check');
      if(strlen($view->msg) > 0)
      {//エラーあり
        $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
        $view->contents = $view->htmlErrMessage();
        echo $view->htmlView();
        return;
      }

      /*****************************/
      //  セッション変数設定
      /*****************************/
      $db->admin_id = $admin_id_;
      $db->admin_pass_word = $admin_pass_word_;
      $dc->SetSession($dc->SetDataToSessionItem($db->GetAdminInfo($admin_id_)));
      $dc->SetCookie($admin_id_);
      header('Location: '.$vew->urlarray['admin']); 
    }
  }

  /*****************************/
  //  管理者設定更新
  /*****************************/
  if(isset($_POST['btn_setting']))
  {//カラー設定
    $board_backcolor_ = CheckColor($_POST['comcolor'], $_POST['free_comcolor']);
    $comment_backcolor_ = CheckColor($_POST['viewcolor'], $_POST['free_viewcolor']);
    $body_backcolor_ = CheckColor($_POST['bodycolor'], $_POST['free_body_color']);
    $subcomment_backcolor_ = CheckColor($_POST['subcolor'], $_POST['free_subgroup_color']);
    $commentboard_backcolor_ = CheckColor($_POST['maincolor'], $_POST['free_main_bk_color']);
    $titel_backcolor_ = CheckColor($_POST['titelcolor'], $_POST['free_titel_bk_color']);
    
    /*****************************/
    //  データ入力チェック
    /*****************************/
    $limitpageline_ = $_POST['limitpageline'];

    $itemarray = array('limitpageline' => $limitpageline_);
    $view->msg = $dc->InputDataCheck($itemarray);
    if(strlen($view->msg) > 0)
    {//エラーあり
      $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
      $view->contents = $view->htmlErrMessage();
      echo $view->htmlView();
      return;
    }

    /*****************************/
    //  DB更新
    /*****************************/
    $db->board_backcolor = $board_backcolor_;
    $db->comment_backcolor = $comment_backcolor_;
    $db->limitpageline = $limitpageline_;
    $db->body_backcolor = $body_backcolor_;
    $db->subcomment_backcolor = $subcomment_backcolor_;
    $db->commentboard_backcolor = $commentboard_backcolor_;
    $db->titel_backcolor = $titel_backcolor_;
    $db->EditAdminInfo($dc->GetSessionAdminID());

    /*****************************/
    //  セッション変数設定
    /*****************************/
    $dc->SetSession($dc->SetDataToSessionItem($db->GetAdminInfo($dc->GetSessionAdminID())));
    
    /*****************************/
    //  更新確認画面表示
    /*****************************/
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
  //  表示
  /*****************************/
  if(!$dc->CheckLogin())
  {// ログアウト時の表示：ログイン画面
    $view->pagetitle = $view->pagetitlearray['adminlogin'];
    $view->contents = $view->htmlAdminCheck();
    echo $view->htmlView();
    return;
  }
  else
  {//ログイン時の表示：設定画面
    $session_data = $dc->GetSession();
    $view->admin_id = $session_data['admin_id'];
    $view->limitpageline = $session_data['limitpageline'];
    $view->board_backcolor = $session_data['board_backcolor'];
    $view->comment_backcolor = $session_data['comment_backcolor'];
    $view->body_backcolor = $session_data['body_backcolor'];
    $view->subcomment_backcolor = $session_data['subcomment_backcolor'];
    $view->commentboard_backcolor = $session_data['commentboard_backcolor'];
    $view->titel_backcolor = $session_data['titel_backcolor'];
    
    $view->pagetitle = $view->pagetitlearray['adminsetting'];
    $view->contents = $view->htmlAdminSetting();
    echo $view->htmlView();
    return;
  }
  
  /*****************************/
  //  入力カラー設定
  /*****************************/
  function CheckColor($color, $free)
  {
    return 0 < strlen($color) ? $color : $free;
  }

?>
