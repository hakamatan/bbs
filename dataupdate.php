<?php
/*************************************************/
//  コメント入力確認＋追加＋更新＋削除
/*************************************************/
  require_once('DBClass.php');
  require_once('ViewClass.php');
  require_once('DataCheckClass.php');

  $db = new DBClass();
  $view = new ViewClass();
  $dc = new DataCheckClass();

  //セッション
  $dc->SessionStart();
  
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
      $delimg = $dc->GetReleaseImage('', $board_id_);
      $db->DeleteBoard($board_id_);
    }
    else
    {
      $delimg = $dc->GetReleaseImage($comment_id_, '');
      $db->DeleteComment($comment_id_);
    }
    $dc->DeleteReleaseImage($delimg);
    
    $view->pagetitle = $view->pagetitlearray['delete'];
    $view->msg = $view->msgarray['ok'];
    $view->urlfile = 2 > $cnt_ ? $view->urlarray['home'] : sprintf($view->urlarray['grp_add'], $board_id_, 1);
    $view->button = $view->buttonarray[1];
    $view->contents = $view->htmlMessage();
    echo $view->htmlView();
    return;
  }

  /*****************************/
  //  コメント入力確認
  /*****************************/
  if(isset($_POST['btn_insertchk']) || isset($_POST['btn_insert']) || isset($_POST['btn_edit']))
  {
    $handlename_ = $_POST["handlename"];
    $title_ = $_POST["title"];
    $comment_ = $_POST["comment"];
    $pass_word_ = $_POST["pass_word"];

    if(isset($_FILES['imagefile']))
    {//添付がある時
      $imagefile_ = $_FILES['imagefile']['tmp_name'];
      $imagefile_name_ = $_FILES['imagefile']['name'];
      $imagefile_size_ = $_FILES['imagefile']['size'];
      $imagefile_err_  = $_FILES['imagefile']['error'];
      $old_imagefile_ = isset($_POST['old_imagefile']) ?  $_POST['old_imagefile'] : "";
      $delcheck_ = isset($_POST['delcheck']) ?  $_POST['delcheck'] : "0";
    }
    else
    {//添付がない時
      $imagefile_ = $_POST['imagefile'];
    }
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
      $itemarray = array('handlename' => $handlename_, 'title' => $title_, 'comment' => $comment_, 'pass_word'=> $pass_word_);
      $view->msg = $dc->InputDataCheck($itemarray);
      if(0 < strlen($imagefile_))
      {//添付がある時のみ
        $imgitemarray = array('imagefile' => $imagefile_, 'imagefile_name' => $imagefile_name_, 'imagefile_size' => $imagefile_size_, 'imagefile_err'=> $imagefile_err_);
        $view->msg .= $dc->InputImgDataCheck($imgitemarray, $view->imagefile);
      }
      if(0 < strlen($view->msg))
      {//エラーあり
        $view->pagetitle = $view->htmlSpanRed($view->pagetitlearray['error']);
        $view->contents = $view->htmlErrMessage();
        echo $view->htmlView();
        return;
      }
      //以前添付したファイルを使用
      print sprintf("delcheck_=%s, old_imagefile_=%s, strlen(imagefile_)=%s  <br>", $delcheck_, $old_imagefile_, strlen($imagefile_));
      if("0" == $delcheck_ && $old_imagefile_ && (1 > strlen($imagefile_)))
      {//添付削除チェックなし・以前添付あり・今回添付なし
        $view->imagefile = $old_imagefile_;
      }
      //添付削除
      $dc->DeleteOldImage($delcheck_, $old_imagefile_, $imagefile_);

      //データチェックOK
      if(0 < strlen($view->imagefile))
      {
        $dc->Zoomout($view->imagefile, $view->width, $view->height); //イメージファイル横縦幅セット
      }
      $view->pagetitle = $view->pagetitlearray['inputcheck'];
      $view->handlename = $handlename_;
      $view->title = $title_;
      $view->comment = $comment_;
      $view->pass_word = $pass_word_;
      $view->urlfile = '';
      $view->contents = $view->htmlCommentCheck();
      echo $view->htmlView();
      return;
    }

    /*****************************/
    //  編集ボタンクリック
    /*****************************/
    if(isset($_POST['btn_edit']))
    {      
      $view->pagetitle = $view->pagetitlearray['add'];
      $view->handlename = $handlename_;
      $view->title = $title_;
      $view->comment = $comment_;
      $view->pass_word = $pass_word_;
      $view->imagefile = $imagefile_;
      $view->contents = $view->htmlCommentInput();
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
          $db->imagefile = $dc->GetFileName($imagefile_);
          
          $db->AddComment();
          $dc->ReleaseImageFile($db->imagefile);
          
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
          $db->imagefile = $dc->GetFileName($imagefile_);
          print sprintf("返信追加 imagefile=%s", $db->imagefile);
          
          $db->AddCommentReturn();
          $dc->ReleaseImageFile($db->imagefile);
          
          $view->pagetitle = $view->pagetitlearray['insert'];
          $view->msg = $view->msgarray['ok'];
          $view->urlfile = sprintf($view->urlarray['grp_add'], $board_id_, 1);
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
          $db->imagefile = $dc->GetFileName($imagefile_);

          $dc->DeleteOldReleaseImage($comment_id_, $db->imagefile);
          $db->EditComment($comment_id_);
          $dc->ReleaseImageFile($db->imagefile);

          $view->pagetitle = $view->pagetitlearray['update'];
          $view->msg = $view->msgarray['ok'];
          $view->urlfile = sprintf($view->urlarray['grp_add'], $board_id_, 1);
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