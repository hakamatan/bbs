<?php
/*************************************************/
//  コメント入力確認＋追加＋更新＋削除
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

  //セッション開始
  session_start();
  session_cache_limiter('private, must-revalidate');

  $db = new DBClass();
  $view = new ViewClass();

  /*****************************/
  //  コメント削除
  /*****************************/
  if(isset($_POST['delete']))
  {
    if(2 > $_GET['cnt'])
    {//1件目
      $db->DeleteBoard($_GET['board_id']);
      $db->DeleteComment($_GET['comment_id']);
    }
    else
    {
      $db->DeleteComment($_GET['comment_id']);
    }
    $view->pagetitle = $view->pagetitlearray['delete'];
    $view->msg = $view->msgarray['ok'];
    $view->urlfile = 2 > $_GET['cnt'] ? $view->urlarray['home'] : sprintf($view->urlarray['grp_add'], $_GET['board_id']);    $view->button = $view->buttonarray[1];
    $view->contents = $view->htmlMessage();
    echo $view->htmlView();
    return;
  }

  /*****************************/
  //  コメント入力確認
  /*****************************/
  if(isset($_POST['insertchk']) || isset($_POST['insert']))
  {
    $handlename = $_POST['handlename'];
    $title = $_POST['title'];
    $comment = $_POST['comment'];
    $pass_word = $_POST['pass_word'];
 
    /*****************************/
    //  エスケープ文字の除去とタグの無効化
    /*****************************/
    $handlename = htmlspecialchars(stripcslashes($handlename));
    $title = htmlspecialchars(stripcslashes($title));
    $comment = htmlspecialchars(stripcslashes($comment));

    /*****************************/
    //  入力確認ボタンクリック
    /*****************************/
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

    /*****************************/
    //  書込みボタンクリック
    /*****************************/
    if(isset($_POST['insert']))
    {
      switch ($_GET['type'])
      {
        case 1:
          /*****************************/
          //  追加
          /*****************************/
          $db->title = $title;
          $db->comment = $comment;
          $db->handlename = $handlename;
          $db->pass_word  = $pass_word;
          
          $db->AddComment();
          
          $view->pagetitle = $view->pagetitlearray['insert'];
          $view->msg = $view->msgarray['ok'];
          $view->urlfile = $view->urlarray['home'];
          $view->button = $view->buttonarray[1];
          $view->contents = $view->htmlMessage();
          echo $view->htmlView();
          return;
          break;
        
        case 2:
          /*****************************/
          //  返信追加
          /*****************************/
          $view->board_id = $_GET['board_id'];
          $db->board_id = $_GET['board_id'];

          $db->title = $title;
          $db->comment = $comment;
          $db->handlename = $handlename;
          $db->pass_word  = $pass_word;
          
          $db->AddCommentReturn();
          
          $view->pagetitle = $view->pagetitlearray['insert'];
          $view->msg = $view->msgarray['ok'];
          $view->urlfile = sprintf($view->urlarray['grp_add'], $_GET['board_id']);
          $view->button = $view->buttonarray[1];
          $view->contents = $view->htmlMessage();
          echo $view->htmlView();
          return;
          break;

        case 3:
          /*****************************/
          //  コメント更新
          /*****************************/
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
          $view->urlfile = sprintf($view->urlarray['grp_add'], $_GET['board_id']);
          $view->button = $view->buttonarray[1];
          $view->contents = $view->htmlMessage();
          echo $view->htmlView();
          return;
          break;

        default:
          /*****************************/
          //
          /*****************************/
          break;
      }
    }
  }
?>