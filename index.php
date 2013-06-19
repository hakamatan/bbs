<?php
/*************************************************/
//  初期画面
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

  $contents = '';
  $view = new ViewClass();
  $db = new DBClass();
  $dc = new DataCheckClass();

  //セッション
  $dc->SessionStart();

  /*****************************/
  //ページリンク作成
  /*****************************/
  $page_ = isset($_GET['page']) ? $_GET['page'] : 1;
  $startrow = 0;  //表示開始レコード
  $allpage_ = 1;  //全ページ
  $allcount = 0;  //全件数

  $pagelimit = $dc->GetPageLimit();
  $startrow = $dc->GetStartRow($page_, $pagelimit);

  /*****************************/
  //  コメント検索入力部作成
  /*****************************/
  $view->urlfile = sprintf($view->urlarray['search'],'','','');
  $contents .= $view->htmlWordSearch();

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
  $view->dt = $dt[0];
  $contents .= $view->htmlTitleView();

  //  全件データ件数取得
  foreach ($dt[1] as $dr)
  {
    $allcount = $dr['count'];
  }
  $view->alldata = $allcount;

  /*****************************/
  //  表示
  /*****************************/
  $view->urlfile = $view->urlarray['homepage'];
  $view->startrow = $startrow + 1;
  $view->page = $page_;
  $view->lastrow = $dc->GetEndRow($page_, $pagelimit, $allcount);
  $view->allpage = $dc->GetAllPage($allcount, $pagelimit);
  $view->pageinfo = $view->htmlPageInformation();
  
  $view->pagetitle = $view->pagetitlearray['add'];
  $view->contents = $contents;
  echo $view->htmlView();
  return;
?>
