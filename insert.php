<?php
  /******************************/
  /*  入力確認  */
  /******************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

    $handlename = $_POST['handlename'];
    $title = $_POST['title'];
    $comment = $_POST['comment'];
    $pass_word = $_POST['pass_word'];
    
    //エスケープ文字の除去とタグの無効化
    $comment = htmlspecialchars(stripcslashes($handlename));
    $comment = htmlspecialchars(stripcslashes($title));
    $comment = htmlspecialchars(stripcslashes($comment));

    $view = new ViewClass();

    //入力確認
    if(isset($_POST['insertchk']))
    {
      //データチェック
      $dc = new DataCheckClass();
      $itemarray = array('handlename' => $handlename, 'title' => $title, 'comment' => $comment, 'pass_word'=> $pass_word);
      $view->msg = $dc->InputDataCheck($itemarray);
      if(strlen($view->msg) > 0)
      {//エラーあり
        $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
        $view->contents = $view->htmlErrMessage();
        echo $view->htmlView();
        return;
      }

      //データチェックOK
      $view->pagetitle = $view->pagetitlearray['inputcheck'];
      $view->handlename = $handlename;
      $view->title = $title;
      $view->comment = $comment;
      $view->pass_word = $pass_word;
      $view->contents = $view->htmlCommentCheck();
      echo $view->htmlView();
      return;
    }

    if(isset($_POST['insert']))
    {
      $db = new DBClass();

      switch ($_GET['type'])
      {
        case 1:
          //コメント追加
          $db->title = $title;
          $db->comment = $comment;
          $db->handlename = $handlename;
          $db->pass_word  = $pass_word;
          
          $db->AddComment();
          
          $view->pagetitle = $view->pagetitlearray['insert'];
          $view->msg = $view->msgarray['ok'];
          $view->button = $view->htmlButtonType('home');
          $view->contents = $view->htmlMessage();
          echo $view->htmlView();
          return;
          break;
        
        case 2:
          //返信追加
          $view->board_id = $_GET['board_id'];
          $db->board_id = $_GET['board_id'];

          $db->title = $title;
          $db->comment = $comment;
          $db->handlename = $handlename;
          $db->pass_word  = $pass_word;
          
          $db->AddCommentReturn();
          
          $view->pagetitle = $view->pagetitlearray['insert'];
          $view->msg = $view->msgarray['ok'];
          $view->button = $view->htmlButtonType('group');
          $view->contents = $view->htmlMessage();
          echo $view->htmlView();
          return;
          break;

        case 3:
          //コメント更新
          $comment_id = $_GET['comment_id'];
          $view->board_id = $_GET['board_id'];

          $db->title = $title;
          $db->comment = $comment;
          $db->handlename = $handlename;
          $db->pass_word  = $pass_word;
          $db->board_id  = $view->board_id;
          $db->comment_id = $comment_id;

          $db->EditComment($comment_id);

          $view->pagetitle = $view->pagetitlearray['update'];
          $view->msg = $view->msgarray['ok'];
          $view->button = $view->htmlButtonType('group');
          $view->contents = $view->htmlMessage();
          echo $view->htmlView();
          return;
          break;

        default:
          break;
      }
    }
?>