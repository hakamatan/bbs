<?php
  /******************************/
  /*  メイン  */
  /******************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
	try
	{
    //コメント入力
    $view = new ViewClass();
    $view->urlfile = 'insertcheck.php?type=1';
    $contents = $view->htmlCommentNewInput();

    //タイトル一覧表示
    $db = new DBClass();  //DB Open
    $db->DBOpen();

    $sql = 'select board.title, board.id, name, board.created_at, board.up_date from board
            left join comment on board.id=comment.board_id order by id desc';
    $sqlret = $db->SelectCol($sql);
    $contents .= $view->htmlTitleViewHeader();
    foreach ($sqlret as $row)
    {
      $view->board_id = $row['id'];
      $view->title = $row['title'];
      $view->handlename = $row['name'];
      $view->add_date = $row['created_at'];
      $view->up_date = $row['up_date'];
      $contents .= $view->htmlTitleView();
   	}
    $contents .= $view->htmlTitleViewFooter();

    $db->DbClose(); //DB Close

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
