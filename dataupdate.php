<?php
/*************************************************/
//  コメント入力確認＋追加＋更新＋削除
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

  //セッション
  session_cache_limiter('private, must-revalidate');
  session_start();

  $db = new DBClass();
  $view = new ViewClass();

  /*****************************/
  //  コメント削除
  /*****************************/
  if(isset($_POST['btn_delete']))
  {
    $board_id_ = $_GET['board_id'];
    $comment_id_ = $_GET['comment_id'];
    $cnt_ = $_GET['cnt'];
    
    if(2 > $cnt_)
    {//1件目
      $db->DeleteBoard($board_id_);
      $db->DeleteComment($comment_id_);
    }
    else
    {
      $db->DeleteComment($comment_id_);
    }
    $view->pagetitle = $view->pagetitlearray['delete'];
    $view->msg = $view->msgarray['ok'];
    $view->urlfile = 2 > $cnt_ ? $view->urlarray['home'] : sprintf($view->urlarray['grp_add'], $board_id_);
    $view->button = $view->buttonarray[1];
    $view->contents = $view->htmlMessage();
    echo $view->htmlView();
    return;
  }

  /*****************************/
  //  コメント入力確認
  /*****************************/
  if(isset($_POST['btn_insertchk']) || isset($_POST['btn_insert']))
  {
    $handlename_ = $_POST['handlename'];
    $title_ = $_POST['title'];
    $comment_ = $_POST['comment'];
    $pass_word_ = $_POST['pass_word'];
 
    /*****************************/
    //  エスケープ文字の除去とタグの無効化
    /*****************************/
    $handlename_ = htmlspecialchars(stripcslashes($handlename_));
    $title_ = htmlspecialchars(stripcslashes($title_));
    $comment_ = htmlspecialchars(stripcslashes($comment_));

    /*****************************/
    //  入力確認ボタンクリック
    /*****************************/
    if(isset($_POST['btn_insertchk']))
    {
      //データチェック
      $dc = new DataCheckClass();
      $itemarray = array('handlename' => $handlename_, 'title' => $title_, 'comment' => $comment_, 'pass_word'=> $pass_word_);
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
      $view->handlename = $handlename_;
      $view->title = $title_;
      $view->comment = $comment_;
      $view->pass_word = $pass_word_;
      $view->contents = $view->htmlCommentCheck();
      echo $view->htmlView();
      return;
    }

    /*****************************/
    //  書込みボタンクリック
    /*****************************/
    if(isset($_POST['btn_insert']))
    {
      switch ($_GET['type'])
      {
        case 1:
          /*****************************/
          //  追加
          /*****************************/
          $db->title = $title_;
          $db->comment = $comment_;
          $db->handlename = $handlename_;
          $db->pass_word  = $pass_word_;
          
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
          $board_id_ = $_GET['board_id'];
          
          $db->board_id = $board_id_;
          $db->title = $title_;
          $db->comment = $comment_;
          $db->handlename = $handlename_;
          $db->pass_word  = $pass_word_;
          
          $db->AddCommentReturn();
          
          $view->pagetitle = $view->pagetitlearray['insert'];
          $view->msg = $view->msgarray['ok'];
          $view->urlfile = sprintf($view->urlarray['grp_add'], $board_id_);
          $view->button = $view->buttonarray[1];
          $view->contents = $view->htmlMessage();
          echo $view->htmlView();
          return;
          break;

        case 3:
          /*****************************/
          //  コメント更新
          /*****************************/
          $comment_id_ = $_GET['comment_id'];
          $board_id_ = $_GET['board_id'];

          $db->title = $title_;
          $db->comment = $comment_;
          $db->handlename = $handlename_;
          $db->pass_word  = $pass_word_;
          $db->board_id  = $board_id_;
          $db->comment_id = $comment_id_;

          $db->EditComment($comment_id_);

          $view->pagetitle = $view->pagetitlearray['update'];
          $view->msg = $view->msgarray['ok'];
          $view->urlfile = sprintf($view->urlarray['grp_add'], $board_id_);
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