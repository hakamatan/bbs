<?php
/*************************************************/
//  グループ処理
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

  session_cache_limiter('private, must-revalidate');
  //セッション開始
  session_start();
  
  $view = new ViewClass();

  /*****************************/
  //  編集・削除ボタンクリック
  /*****************************/
  if(isset($_POST['btn_update']) || isset($_POST['btn_delete']))
  {
    $view->pagetitle = $view->pagetitlearray['keycheck'];
    $view->contents = $view->htmlKeyCheck();
    echo $view->htmlView();
    return;
  }
    
  /*****************************/
  //  確認ボタンクリック
  /*****************************/
  $db = new DBClass();
  $updelkey_ = '';

  if(isset($_POST['btn_keycheck']))
  {
    $comment_id_ = $_GET['comment_id'];
    $updelkey_ = $comment_id_;
    $pass_word_ = $_POST['pass_word'];
    $board_id_ = $_GET['board_id'];
    $line_ = $_GET['cnt'];

    /*****************************/
    //  更新・削除キーチェック
    /*****************************/
    $dc = new DataCheckClass();
    $view->msg = $dc->Pass_WordCheck($pass_word_, $comment_id_);
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
      $view->pagetitle = $view->pagetitlearray['delete'];
      $view->msg = '本当に削除していいですか？';
      $urlyes = sprintf($view->urlarray['del'], $board_id_, $comment_id_,$line_);
      $urlno = sprintf($view->urlarray['grp_add'], $board_id_);
      print '$urlyes->'.$urlyes.';$urlno->'.$urlno.'<br>';
      $view->urlfile = array($urlyes, $urlno);
      $view->button = $view->buttonarray[2];
      $view->button_name = array('btn_delete','btn_cancel');
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

  $board_id_ = $_GET['board_id'];
  $dt = $db->GetGroupView($board_id_);
  foreach ($dt as $dr)
  {
    $view->comment_id = $dr['comment_id'];
    $view->title = $dr['title'];
    $view->handlename = $dr['handlename'];
    $view->comment = $dr['comment'];
    $view->up_date = $dr['up_date'];
    $view->cnt = $cnt;
    $view->urlfile = sprintf($view->urlarray['grp_edit'], $board_id_, $dr['comment_id'], $cnt);
    if(2 > $cnt)
    {//1件目
      $retitle = 'Re:'.$view->title;
      $body .= $view->htmlGroupViewFirst();
    }
    else
    {
      $subbody .= $view->htmlSubGroupView();
    }
    if($updelkey_ == $view->comment_id)
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

  if(isset($_POST['btn_keycheck']))
  {//更新
    $view->comment_id = $up_comment_id;
    $view->board_id = $up_board_id;
    $view->comment = $up_comment;
    $view->title = $up_title;
    $view->handlename = $up_handlename;
    $view->pass_word = $up_pass_word;
    $url = sprintf($view->urlarray['edit'], $board_id_, $comment_id_);
  }
  else
  {//返信新規
    $url = sprintf($view->urlarray['returnadd'], $board_id_);
  }
  $view->urlfile = $url;
  $contents .= (isset($_POST['btn_keycheck']) ? $view->htmlCommentInput() : $view->htmlCommentNewInput($retitle));

  /*****************************/
  //  表示
  /*****************************/
  $view->pagetitle = $view->pagetitlearray['add'];
  $view->contents = $contents;
  echo $view->htmlView();
  return;

?>
