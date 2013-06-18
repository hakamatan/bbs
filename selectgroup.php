<?php
/*************************************************/
//  グループ処理
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

  //セッション
  session_cache_limiter('private, must-revalidate');
  session_start();
  
  $view = new ViewClass();
  $dc = new DataCheckClass();

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
      $urlyes = sprintf($view->urlarray['del'], $board_id_, $comment_id_, $line_);
      $urlno = sprintf($view->urlarray['grp_add'], $board_id_, 1);
      //print '$urlyes->'.$urlyes.';$urlno->'.$urlno.'<br>';
      $view->urlfile = array($urlyes, $urlno);
      $view->button = $view->buttonarray[2];
      $view->button_name = array('btn_delete','btn_cancel');
      $view->contents = $view->htmlMessage();
      echo $view->htmlView();
      return;
    }
  }

  /*****************************/
  //ページリンク作成
  /*****************************/
  $page_ = isset($_GET['page']) ? $_GET['page'] : 1;
  $startrow = 0;  //表示開始レコード
  $allpage_ = 1;  //全ページ
  $allcount = 0;  //全件数

  $pagelimit = isset($_SESSION['limitpageline']) ? $_SESSION['limitpageline'] : $db->pagelimit;
  $startrow = $dc->GetStartRow($page_, $pagelimit);

  /*****************************/
  //  グループ一覧部作成
  /*****************************/
  $board_id_ = $_GET['board_id'];
  $starow = (1 == $page_) ? $startrow + 1 : $startrow;  //2件目以降の調整

  $dt = $db->GetGroupView($board_id_, $starow, $pagelimit);

  //  グループ一覧取得
  list($retitle,$body,$updel_key) = SetGroupView($dt[2], 1, $updelkey_, $page_, $board_id_);  //1件目
  list($dummy,$subbody,$updel_keysub) = SetGroupView($dt[0], 2, $updelkey_, $page_, $board_id_);  //2件目以降
  $contents = $view->htmlGroupView($body, $subbody);

  //  全件データ件数取得
  foreach ($dt[1] as $dr)
  {
    $allcount = $dr['count'];
  }
  $view->alldata = $allcount;
  $allpage = $dc->GetAllPage($allcount, $pagelimit);

  /*****************************/
  //  コメント入力部作成
  /*****************************/
  if(isset($_POST['btn_keycheck']))
  {//更新
    $key = ($updel_key != null) ? $updel_key : $updel_keysub;
    $view->comment_id = $key['comment_id'];
    $view->board_id = $key['board_id'];
    $view->comment = $key['comment'];
    $view->title = $key['title'];
    $view->handlename = $key['handlename'];
    $view->pass_word = $key['pass_word'];
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
  $urlfile = sprintf($view->urlarray['grp_add'], $board_id_, '%s');
  $view->pageinfo = $view->htmlPageInformation($page_, $startrow + 1, $urlfile, $dc->GetEndRow($page_, $pagelimit, $allcount), $allpage);
  $view->pagetitle = $view->pagetitlearray['group'];
  $view->contents = $contents;
  echo $view->htmlView();
  return;

  /*****************************/
  //  一覧データ編集
  /*****************************/
  function SetGroupView($dt, $cnt, $updelkey, $page, $board_id)
  {
    $view = new ViewClass();
    $retitle = '';
    $body = '';
    $key = null;
    foreach ($dt as $dr)
    {
      $view->comment_id = $dr['comment_id'];
      $view->title = $dr['title'];
      $view->handlename = $dr['handlename'];
      $view->comment = $dr['comment'];
      $view->up_date = $dr['up_date'];
      $view->cnt = $cnt;
      $view->urlfile = sprintf($view->urlarray['grp_edit'], $board_id, $dr['comment_id'], $cnt, $page);
      if(2 > $cnt)
      {//1件目
        $retitle = 'Re:'.$view->title;
        $body .= $view->htmlGroupViewFirst();
      }
      else
      {
        $body .= $view->htmlSubGroupView();
      }  
      if($updelkey == $view->comment_id)
      {
        $key = array('comment_id'=>$dr['comment_id'], 'board_id'=>$dr['board_id'], 'comment'=>$dr['comment'], 'title'=>$dr['title'], 'handlename'=>$dr['handlename'], 'pass_word'=> $dr['pass_word']);
      }
      $cnt++;
    }
    return array($retitle, $body, $key);
  }
?>
