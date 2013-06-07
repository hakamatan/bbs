<?php
  /******************************/
  /*  入力確認画面  */
  /******************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

	try
	{
    $handlename = $_POST['handlename'];
    $title = $_POST['title'];
    $comment = $_POST['comment'];
    $pass_word = $_POST['pass_word'];

    $view = new ViewClass();

    //入力確認
    if(isset($_POST['insertchk']))
    {
      //データチェック
      $check = new DataCheckClass();
      $msg= $check->InputDataCheck($handlename, $title, $comment, $pass_word);
      if(strlen($msg)>0)
      {//エラーあり
        //失敗画面表示
        $view->pagetitle = $view->htmlSpanRed('エラー：確認してください。');
        $view->msg = $view->htmlSpanRed($msg);
        $view->button = $view->htmlButtonType();
        $view->contents = $view->htmlMessage();
        echo $view->htmlView();
        return;
      }

      //表示
      $view->pagetitle = 'コメント入力確認';
      $view->handlename = $handlename;
      $view->title = $title;
      $view->comment = $comment;
      $view->pass_word = $pass_word;
      $view->contents = $view->htmlCommentCheck();
      echo $view->htmlView();
      return;
    }

    $type = $_GET['type'];

    if(isset($_POST['insert']))
    {
      if($type==1)
      {
        //コメント追加
        $db = new DBClass();  //DB Open
        $db->DBOpen();

        $db->title = $title;
        $db->comment = $comment;
        $db->handlename = $handlename;
        $db->pass_word  = $pass_word;
        $db->InsertComment();

        $db->DbClose(); //DB Close

        $view->pagetitle = '追加処理';
        $view->msg = '正常に処理されました。';
        $view->button = $view->htmlButtonType('1');
        $view->contents = $view->htmlMessage();
        echo $view->htmlView();
        return;
      }
      else
      {
        $comment_id = $_GET['comment_id'];
        print $comment_id.'<----';
        //更新
        $db = new DBClass();  //DB Open
        $db->DBOpen();

        $db->title = $title;
        $db->comment = $comment;
        $db->handlename = $handlename;
        $db->pass_word  = $pass_word;
        $db->board_id  = $pass_word;
        $db->comment_id = $comment_id;
        $db->UpdateComment();
      }
    }

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