<?php
/*************************************************/
//  グループ処理
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');
  
  $view = new ViewClass();
  $dc = new DataCheckClass();

  //セッション
  $dc->SessionStart();
  $contents_admin = '';
  /*****************************/
  //  編集・削除ボタンクリック
  /*****************************/
  if(isset($_POST['btn_update']) || isset($_POST['btn_delete']))
  {
    if(!$dc->CheckLogin())
    {//ログアウト中
      $view->pagetitle = $view->pagetitlearray['keycheck'];
      $view->contents = $view->htmlKeyCheck();
      echo $view->htmlView();
      return;
    }
    else
    {//ログイン中
      $comment_id_ = $_GET['comment_id'];
      $updelkey_ = $comment_id_;
      $board_id_ = $_GET['board_id'];
      $line_ = $_GET['cnt'];
      if(2 == $_GET['type'])
      {//削除
        CheckDelete($board_id_, $comment_id_, $line_);
        return;
      }
    }
  }
    
  /*****************************/
  //  確認ボタンクリック
  /*****************************/
  $db = new DBClass();

  if(isset($_POST['btn_keycheck']))
  {
    $pass_word_ = $_POST['pass_word'];
    $comment_id_ = $_GET['comment_id'];
    $updelkey_ = $comment_id_;
    $board_id_ = $_GET['board_id'];
    $line_ = $_GET['cnt'];

    /*****************************/
    //  更新・削除キーチェック
    /*****************************/
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
      CheckDelete($board_id_, $comment_id_, $line_);
      return;
    }
  }

  /*****************************/
  //ページリンク作成
  /*****************************/
  $page_ = isset($_GET['page']) ? $_GET['page'] : 1;
  $dc->GetPageInitialeVlaue($startrow, $allpage_, $allcount);//表示開始レコード//全ページ//全件数
  //print sprintf("startrow=%s, allpage_=%s, allcount=%s <br>", $startrow, $allpage_, $allcount);

  $pagelimit = $dc->GetPageLimit();
  $startrow = $dc->GetStartRow($page_, $pagelimit);

  /*****************************/
  //  グループ一覧部作成
  /*****************************/
  $board_id_ = $_GET['board_id'];
  $starow = (1 == $page_) ? $startrow + 1 : $startrow;  //2件目以降の調整

  $dt = $db->GetGroupView($board_id_, $starow, $pagelimit);

  $view->board_id = $board_id_;
  $view->page = $page_;

  //グループ一覧取得
  $view->dt = array($dt[2], $dt[0]);
  $view->cnt = array(1, 2);
  $contents = $view->htmlGroupView();
  $retitle = sprintf("Re:%s", $view->retitle);

  //  全件データ件数取得
  $allcount = $dc->GetAllDataCount($dt[1]);
  $view->alldata = $allcount;

  /*****************************/
  //  コメント入力部作成
  /*****************************/
  if(isset($_POST['btn_keycheck']) || (isset($_GET['type']) && 1==$_GET['type']))
  {//更新
    $dt = $db->GetComment($updelkey_);
    foreach($dt as $dr)
    {
      $view->comment_id = $dr['comment_id'];
      $view->board_id = $dr['board_id'];
      $view->comment = $dr['comment'];
      $view->title = $dr['title'];
      $view->handlename = $dr['handlename'];
      $view->pass_word = $dr['pass_word'];
    }
    $view->urlfile = sprintf($view->urlarray['edit'], $board_id_, $comment_id_);
    $contents .= $view->htmlCommentInput();
  }
  else
  {//返信新規
    $view->urlfile = sprintf($view->urlarray['returnadd'], $board_id_);
    $contents .= $view->htmlCommentNewInput($retitle);
  }
  

  /*****************************/
  //  表示
  /*****************************/
  $view->urlfile = sprintf($view->urlarray['grp_add'], $board_id_, '%s');
  $view->page = $page_;
  $view->startrow = $startrow + 1;
  $view->lastrow = $dc->GetEndRow($page_, $pagelimit, $allcount);
  $view->allpage = $dc->GetAllPage($allcount, $pagelimit);
  $view->pageinfo = $view->htmlPageInformation();
  
  $view->pagetitle = $view->pagetitlearray['group'];
  $view->contents = $contents;
  echo $view->htmlView();
  return;

  /*****************************/
  //  削除確認
  /*****************************/
  function CheckDelete($board_id, $comment_id, $line)
  {
    $view = new ViewClass();
    
    $view->pagetitle = $view->pagetitlearray['delete'];
    $view->msg = '本当に削除していいですか？';
    $urlyes = sprintf($view->urlarray['del'], $board_id, $comment_id, $line);
    $urlno = sprintf($view->urlarray['grp_add'], $board_id, 1);
    $view->urlfile = array($urlyes, $urlno);
    $view->button = $view->buttonarray[2];
    $view->button_name = array('btn_delete','btn_cancel');
    $view->contents = $view->htmlMessage();
    echo $view->htmlView();
 }

?>
