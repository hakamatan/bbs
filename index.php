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
    $body = $view->htmlComentInput();

    //タイトル一覧表示
    $db = new DBClass();  //DB Open
    $db->DBOpen();
//    $sql = 'select board.title, board.id, name, board.created_at, board.up_date from board
//            left join comment on board.id=comment.board_id order by id desc';
//    $sqlret = $db->SelectCol($sql);
    $sqlret = $db->SelectTitleView();

    $body .= $view->htmlTitleViewHeader();
    foreach ($sqlret as $row)
    {
      $view->bord_id = $row['id'];
      $view->title = $row['title'];
      $view->handlename = $row['name'];
      $view->add_date = $row['created_at'];
      $view->up_date = $row['up_date'];
      $body .= $view->htmlTitleView();
   	}
    $body .= $view->htmlTitleViewFooter();

    $db->DbClose(); //DB Close

    //表示
    $view->pagetitle = 'コメント入力';
    $view->contents = $body;
    echo $view->htmlView();

	}
	catch (PDOException $e)
	{
    print('Error:'.$e->getMessage());
    die();
	}
?>
