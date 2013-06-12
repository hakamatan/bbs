<?php
/*************************************************/
//  グループ処理
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

  //セッション開始
  session_start();

  $view = new ViewClass();

  /*****************************/
  //  編集・削除ボタンクリック
  /*****************************/
  if(isset($_POST['update']) || isset($_POST['delete']))
  {
    $view->board_id = $_GET['board_id'];
    $view->pagetitle = $view->pagetitlearray['keycheck'];
    $view->contents = $view->htmlKeyCheck();
    echo $view->htmlView();
    return;
  }
    
  /*****************************/
  //  確認ボタンクリック
  /*****************************/
  $db = new DBClass();
  $updelkey = '';

  if(isset($_POST['keycheck']))
  {
    $updelkey = $_GET['comment_id'];

    /*****************************/
    //  更新・削除キーチェック
    /*****************************/
    $dc = new DataCheckClass();
    $view->msg = $dc->Pass_WordCheck($_POST['pass_word'], $_GET['comment_id']);
    if(strlen($view->msg) > 0)
    {//エラー
      $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
      $view->contents = $view->htmlErrMessage();
      echo $view->htmlView();
      return;
    }
    
    /*****************************/
    //  コメント削除
    /*****************************/
    if(2 == $_GET['type'])
    {
      $cnt = $_GET['cnt'];
      if (1 < $cnt)
      {
        $db->DeleteComment($_GET['comment_id']);
      }
      else
      {
        $db->DeleteBoard($_GET['board_id']);
        $db->DeleteComment($_GET['comment_id']);
      }
      $view->board_id = $_GET['board_id'];
      $view->pagetitle = $view->pagetitlearray['delete'];
      $view->msg = $view->msgarray['ok'];
      $view->button = 1 < $cnt ? $view->htmlButtonType('group') : $view->htmlButtonType('home');
      $view->contents = $view->htmlMessage();
      echo $view->htmlView();
      return;
    }
  }

  /*****************************/
  //  グループ一覧部作成
  /*****************************/
  $contents = '';
  $body = '';
  $subbody = '';
  $cnt = 1;

  $dt = $db->GetGroupView($_GET['board_id']);
  $view->board_id = $_GET['board_id'];
  $view->urlfile = $view->urlarray['group'];
  foreach ($dt as $dr)
  {
    $view->comment_id = $dr['comment_id'];
    $view->title = $dr['title'];
    $view->handlename = $dr['handlename'];
    $view->comment = $dr['comment'];
    $view->up_date = $dr['up_date'];
    $view->cnt = $cnt;
    if(2 > $cnt)
    {//1件目
      $retitle = 'Re:'.$view->title;
      $body .= $view->htmlGroupViewFirst();
    }
    else
    {
      $subbody .= $view->htmlSubGroupView();
    }
    if($updelkey == $view->comment_id)
    {
      $up_comment_id = $dr['comment_id'];
      $up_board_id = $dr['board_id'];
      $up_comment = $dr['comment'];
      $up_title = $dr['title'];
      $up_handlename = $dr['handlename'];
      $up_pass_word = $dr['pass_word'];
    }
    $cnt++;
 	}
  $contents .= $view->htmlGroupView($body, $subbody);

  /*****************************/
  //  コメント入力部作成
  /*****************************/
  $keycheck = '&board_id='.$view->board_id;
  $url = '';

  if(isset($_POST['keycheck']))
  {//更新
    $view->comment_id = $up_comment_id;
    $view->board_id = $up_board_id;
    $view->comment = $up_comment;
    $view->title = $up_title;
    $view->handlename = $up_handlename;
    $view->pass_word = $up_pass_word;
    $url = $view->urlarray['edit'];
    $url .= $keycheck.'&comment_id='.$view->comment_id;
  }
  else
  {//返信新規
    $url = $view->urlarray['returnadd'];
    $url.= $keycheck;
  }
  $view->urlfile = $url;
  $contents .= (isset($_POST['keycheck']) ? $view->htmlCommentInput() : $view->htmlCommentNewInput($retitle));

  /*****************************/
  //  表示
  /*****************************/
  $view->pagetitle = $view->pagetitlearray['add'];
  $view->contents = $contents;
  echo $view->htmlView();
  return;

?>
