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

  if($_SERVER["REQUEST_METHOD"] != "POST")
  {
    $orand_ = $_GET['orand'];
    $search_word_ = $_GET['word'];
    $page_ = $_GET['page'];
  }
  else
  {
    $orand_ = $_POST['orand'];
    $search_word_ = $_POST['search_word'];
    $page_ = 1;
  }

  /*****************************/
  //  ページリンク作成
  /*****************************/
  $startrow = 0;  //表示開始レコード
  $allpage_ = 1;  //全ページ
  $allcount = 0;  //全件数

  $pagelimit = $dc->GetPagelimit();
  $startrow = $dc->GetStartRow($page_, $pagelimit);

  /*****************************/
  //  コメント検索入力部作成
  /*****************************/
  $view->urlfile = sprintf($view->urlarray['search'], $search_word_, $orand_, $page_);
  $view->search_word = $search_word_;
  $contents .= $view->htmlWordSearch();

  /*****************************/
  //  コメント入力部作成
  /*****************************/
  $view->urlfile = $view->urlarray['add'];
  $contents .= $view->htmlCommentNewInput();

  /*****************************/
  //  検索文字チェック
  /*****************************/
  //エスケープ文字除去
  $word = stripcslashes($search_word_);
  //キーワードの前後のスペース除去
  $word = trim($word);
  ////全角スペースの半角変換と半角カナの全角変換
  //$word = mb_convert_kana($word, "sKV", "utf-8");
  //区切り文字半角スペースに置換
  $not_words = array(",", "、", "　", "。", ".");
  $word = str_replace($not_words, ' ', $word);
  //半角スペースで配列
  $word = explode(" ", $word);

  /*****************************/
  //  検索結果作成
  /*****************************/
  //結果一覧取得
  $dt = $db->GetCommentView($word, $orand_, $startrow, $pagelimit);
  $view->dt = array($dt[0], null);
  $view->cnt = array(1, 2);
  $contents .= $view->htmlGroupView('search');

  //  全件データ件数取得
  foreach ($dt[1] as $dr)
  {
    $allcount = $dr['count'];
 	}
  $view->alldata = $allcount;

  /*****************************/
  //  表示
  /*****************************/
  $view->urlfile = sprintf($view->urlarray['search'], $search_word_, $orand_, '%s');
  $view->page = $page_;
  $view->startrow = $startrow + 1;
  $view->lastrow = $dc->GetEndRow($page_, $pagelimit, $allcount);
  $view->allpage = $dc->GetAllPage($allcount, $pagelimit);
  $view->pageinfo = $view->htmlPageInformation();

  $view->pagetitle = $view->pagetitlearray['search'];
  $view->contents = $contents;
  echo $view->htmlView();
  return;

?>
