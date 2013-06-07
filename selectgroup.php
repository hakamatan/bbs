<?php
  /******************************/
  /*  グループ一覧  */
  /******************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');

	try
	{
    //グループ一覧表示
    $db = new DBClass();  //DB Open
    $db->DBOpen();

    $view = new ViewClass();

    if(isset($_POST['update']) || isset($_POST['delete']))
    {
      $view->board_id = $_GET['board_id'];
      $view->pagetitle = '更新・削除キーチェック';
      $view->contents = $view->htmlKeyCheck();
      echo $view->htmlView();
      return;
    }

    if(isset($_POST['keycheck']))
    {
      $comment_id = $_GET['comment_id'];
      $pass_word = $_POST['pass_word'];

      $sql = 'select id, board_id, contents, create_at,name, pass_word, Imgfile, up_date, title from comment
              where id = '.$comment_id;
      print $sql.'<br>';
      $sqlret = $db->SelectCol($sql);
      foreach ($sqlret as $row)
      {
        $view->comment_id = $row['id'];
        $view->board_id = $row['board_id'];
        $view->comment = $row['contents'];
        $view->add_date = $row['create_at'];
        $view->handlename = $row['name'];
        $view->pass_word = $row['pass_word'];
        $view->up_date = $row['up_date'];
        $view->title = $row['title'];
     	}
      //キーチェック
      if($view->pass_word != $pass_word)
      {
        $db->DbClose(); //DB Close
        $view->pagetitle = $view->htmlSpanRed('エラー');
        $view->msg = $view->htmlSpanRed('キーが一致しません。');
        $view->button = $view->htmlButtonType();
        $view->contents = $view->htmlMessage();
        echo $view->htmlView();
        return;
      }
    }

    $view->board_id = $_GET['board_id'];

    $sql = "select c.id as comment_id, c.title as ctitle, c.name, c.up_date, c.contents as comment, c.create_at, b.title as btitle from comment as c 
join board as b on c.board_id = b.id where c.board_id = ".$view->board_id." order by c.id";
    $sqlret = $db->SelectCol($sql);
    $contents = '';
    $cnt = 0;
    foreach ($sqlret as $row)
    {
      $view->comment_id = $row['comment_id'];
      $view->title = $row['ctitle'];
      $view->handlename = $row['name'];
      $view->comment = $row['comment'];
      $adddate = $row['create_at'];
      $update = $row['up_date'];
      $view->up_date = ('0000-00-00 00:00:00' == $update ? $adddate : $update);
      $contents .= (1 > $cnt ? $view->htmlGroupView() : $view->htmlSubGroupView());
      $cnt++;
   	}
    $contents .= $view->htmlGroupViewEnd();

    $db->DbClose(); //DB Close

    //コメント入力
    $view->urlfile = 'insertcheck.php?type=2&comment_id='.$view->comment_id;
    $contents .= (isset($_POST['keycheck']) ? $view->htmlCommentInput() : $view->htmlCommentNewInput());
    //$contents .= $view->htmlCommentNewInput();

    //表示
    $view->pagetitle = 'コメント入力';
    $view->contents = $contents;
    echo $view->htmlView();
    return;
    

	}
	catch (PDOException $e)
	{//異常終了
    //失敗画面表示
    $view->pagetitle = $view->htmlSpanRed('エラー：異常終了');
    $view->msg = $view->htmlSpanRed($e->getMessage());
    $view->button = $view->htmlButtonType();
    $view->contents = $view->htmlMessage();
    echo $view->htmlView();
    die();
	}

?>
