<?php
/*************************************************/
//  初期画面
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');

  //セッション
  session_cache_limiter('private, must-revalidate');
  session_start();

  $contents = '';
  $view = new ViewClass();
  $db = new DBClass();

  /*****************************/
  //  コメント検索入力部作成
  /*****************************/
  $contents .= $view->htmlWordSearch();

  /*****************************/
  //  コメント入力部作成
  /*****************************/
  $view->urlfile = $view->urlarray['add'];
  $contents .= $view->htmlCommentNewInput();

  /*****************************/
  //  検索ボタンクリック
  /*****************************/
  if(isset($_POST['btn_search']))
  {
    $orand_ = $_POST['orand'];
    $search_word_ = $_POST['search_word'];
    
    //エスケープ文字除去
    $search_word_ = stripcslashes($search_word_);
    //キーワードの前後のスペース除去
    $search_word_ = trim($search_word_);
    ////全角スペースの半角変換と半角カナの全角変換
    //$search_word_ = mb_convert_kana($search_word_, "sKV", "utf-8");
    //区切り文字半角スペースに置換
    $not_words = array(",", "、", "　", "。", ".");
    $search_word_ = str_replace($not_words, ' ', $search_word_);
    //半角スペースで配列
    $search_word_ = explode(" ", $search_word_);

    $dt = $db->GetCommentView($search_word_, $orand_);
    $body = '';
    foreach ($dt as $dr)
    {
      $view->board_id = $dr['board_id'];
      $view->comment_id = $dr['comment_id'];
      $view->title = $dr['title'];
      $view->handlename = $dr['handlename'];
      $view->comment = $dr['comment'];
      $view->up_date = $dr['up_date'];
      $body .= $view->htmlGroupView($view->htmlGroupViewFirst('search'), '');
 	  }
    $contents .= $body;
    
    $view->pagetitle = $view->pagetitlearray['search'];
    $view->contents = $contents;
    echo $view->htmlView();
    return;
  }

  /*****************************/
  //  全件データ件数取得
  /*****************************/
  $dt = $db->GetTitleView();
  $body = '';
  foreach ($dt as $dr)
  {
    $view->alldata = $dr['count'];
 	}
  $startrow = 1;
  $nowpage_ = 1;
  $allpage =  $view->alldata % $db->pagelimit;
  print sprintf("allpage =  %d ;<br>", $allpage);

  /*****************************/
  //  ページボタンクリック
  /*****************************/
  if(isset($_POST['btn_top']) || isset($_POST['btn_next']) || isset($_POST['btn_back']) || isset($_POST['btn_bottom']))
  {
    $nowpage_ = $_POST['nowpage'];
    
    if(isset($_POST['btn_top']))
    {
      $startrow = 1;
      print sprintf("btn_top =  %d ;<br>", $startrow);
    }
    else if(isset($_POST['btn_next']))
    {
      $startrow = $nowpage_ * $db->pagelimit + 1;
      print sprintf("btn_next =  %d ;<br>", $startrow);
    }
    else if(isset($_POST['btn_back']))
    {
      $startrow = ($nowpage_ - 1) * $db->pagelimit + 1;
      print sprintf("btn_back =  %d ;<br>", $startrow);
    }
    else
    {
      $startrow = ($allpage - 1) * $db->pagelimit + 1;
      print sprintf("btn_bottom =  %d ;<br>", $startrow);
    }
  }

  /*****************************/
  //タイトル一覧表示部作成
  /*****************************/
  $dt = $db->GetTitleView($startrow);
  $body = '';
  foreach ($dt as $dr)
  {
    $view->board_id = $dr['board_id'];
    $view->title = $dr['title'] != $dr['subject'] ? $dr['subject'] : $dr['title'];
    $view->handlename = $dr['handlename'];
    $view->add_date = $dr['add_date'];
    $view->up_date = $dr['add_date'] != $dr['up_date'] ? $dr['up_date'] : '0000-00-00 00:00:00';
    $body .= $view->htmlTitleViewBody();
 	}
  $contents .= $view->htmlTitleView($body);

  /*****************************/
  //  表示
  /*****************************/
  $view->urlfile = sprintf($view->urlarray['home_page'], $nowpage_);
  $view->pageinfo = $view->htmlPageInfo($nowpage_, $allpage);
  $view->pagetitle = $view->pagetitlearray['add'];
  $view->contents = $contents;
  echo $view->htmlView();
  return;
?>
