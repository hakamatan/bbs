<?php
/*************************************************/
//  初期画面
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

  //セッション
  session_cache_limiter('private, must-revalidate');
  session_start();

  $contents = '';
  $view = new ViewClass();
  $db = new DBClass();
  $dc = new DataCheckClass();

  $page_ = isset($_GET['page']) ? $_GET['page'] : 1;
  $startrow = 0;  //表示開始レコード
  $allpage_ = 1;  //全ページ
  $allcount = 0;  //全件数
  
  /*****************************/
  //ページリンク作成
  /*****************************/
  $pagelimit = isset($_SESSION['limitpageline']) ? $_SESSION['limitpageline'] : $db->pagelimit;
  $startrow = $dc->GetStartRow($page_, $pagelimit);

  /*****************************/
  //  コメント検索入力部作成
  /*****************************/
  $urlfile = sprintf($view->urlarray['search'],'','','');
  $contents .= $view->htmlWordSearch($urlfile, '');

  /*****************************/
  //  コメント入力部作成
  /*****************************/
  $view->urlfile = $view->urlarray['add'];
  $contents .= $view->htmlCommentNewInput();

  /*****************************/
  //タイトル一覧表示部作成
  /*****************************/
  //一覧取得
  $dt = $db->GetTitleView($startrow, $pagelimit);
  $body = '';
  foreach ($dt[0] as $dr)
  {
    $view->board_id = $dr['board_id'];
    $view->title = $dr['title'] != $dr['subject'] ? $dr['subject'] : $dr['title'];
    $view->handlename = $dr['handlename'];
    $view->add_date = $dr['add_date'];
    $view->up_date = $dr['add_date'] != $dr['up_date'] ? $dr['up_date'] : '0000-00-00 00:00:00';
    $body .= $view->htmlTitleViewBody();
  }
  $contents .= $view->htmlTitleView($body);

  //  全件データ件数取得
  foreach ($dt[1] as $dr)
  {
    $allcount = $dr['count'];
  }
  $view->alldata = $allcount;
  $allpage = $dc->GetAllPage($allcount, $pagelimit);

  /*****************************/
  //  表示
  /*****************************/
  $view->pageinfo = $view->htmlPageInformation($page_, $startrow + 1, $view->urlarray['homepage'], $dc->GetEndRow($page_, $pagelimit, $allcount), $allpage);
  $view->pagetitle = $view->pagetitlearray['add'];
  $view->contents = $contents;
  echo $view->htmlView();
  return;
?>
