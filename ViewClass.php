<?php
  /***************************/
  //ViewClassクラス
  /***************************/
class ViewClass
{
  //ページタイトル
  public $pagetitlearray = array(
        'add'=>'コメント入力',
        'edit'=>'コメント修正',
        'exception'=>'エラー：異常終了',
        'error'=>'エラー',
        'insert'=>'追加処理',
        'update'=>'更新処理',
        'delete'=>'削除処理',
        'inputcheck'=>'コメント入力確認',
        'keycheck'=>'更新・削除キーチェック');
  
  //ファイル
  public $urlarray = array(
        'home'=>'index.php',
        'add'=>'insert.php?type=1',
        'returnadd'=>'insert.php?type=2',
        'edit'=>'insert.php?type=3',
        'group'=>'selectgroup.php');

  //ファイル
  public $msgarray = array(
        'ok'=>'正常に処理されました。',
        'keycheck'=>'キーが一致しません。');

  //プロパティ
  public $contents;
  public $pagetitle;
  public $handlename;
  public $title;
  public $comment;
  public $up_date;
  public $add_date;
  public $pass_word;
  public $msg;
  public $button;
  public $board_id;
  public $comment_id;
  public $urlfile;
  public $cnt;

  /***************************/
  //ページ
  /***************************/
  function htmlView()
  {
    $ret = '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $ret .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">';
    $ret .= '
    <head>
    	<meta http-equiv="Contrnt-Type" content="text/html; charset=UTF-8" />
      <link href="css/common.css" rel="stylesheet" type="text/css" />
    	<title>掲示板入門編</title>
    </head>';
    $ret .= '
    <body>
    <center>
    <div id="maincontents">
      <br>
      <div id="maintitle"><h3>■　掲示板　■</h3></div>
      <div id="homelnk"><a href="./">&nbsp;HOME&nbsp;</a></div>
      <div id="pagetitle">'.$this->pagetitle.'</div>
      <!--コンテンツ START -->
      <div id="contents">';
    $ret .= $this->contents;
    $ret .= '
      </div>
      <!--コンテンツ END -->
      <br>
    </div>
    </center>
    </body>
    </html>';
    return $ret;
  }

  /***************************/
  //タイトル一覧
  /***************************/
  function htmlTitleView($TitleViewBody)
  {
    $ret = '
    <!--(2:タイトル一覧 START)-->
        <div class="group2">
          <table id="titlegrp" cellspacing="1px">
          <tr><th>タイトル</th><th>投稿者</th><th>作成日</th><th>最終更新日</th></tr>';
    $ret .= $TitleViewBody;
    $ret .= '
          </table>
        </div>
    <!--(2:タイトル一覧 END)-->';
    return $ret;
  }

  /***************************/
  //タイトル一覧本体
  /***************************/
  function htmlTitleViewBody()
  {
    return '
          <tr><td class="left"><a href="selectgroup.php?board_id='.$this->board_id.'">'.$this->title.'</a></td>
              <td class="left">'.$this->handlename.'</td>
              <td>'.$this->add_date.'</td>
              <td>'.$this->up_date.'</td>
          </tr>';
  }

  /***************************/
  //コメント入力
  /***************************/
  function htmlCommentNewInput($title = '')
  {
    $this->handlename = '';
    $this->title = $title;
    $this->comment = '';
    $this->pass_word = '';

    return $this->htmlCommentInput();
  }

  /***************************/
  //コメント入力
  /***************************/
  function htmlCommentInput()
  {
    return
    '<!--(3:コメント入力 START)-->
        <div class="group0">
          <form action="'.$this->urlfile.'" method="post">
            <table id="newdata">
            <tr><th>名前</th><td><input class="jpn" size="51" type="text" name="handlename" value="'.$this->handlename.'"></td></tr>
            <tr><th>タイトル</th><td><input class="jpn" size="51" type="text" name="title" value="'.$this->title.'"></td></tr>
            <tr><th class="top">メッセージ</th><td><textarea class="jpn" rows="7" cols="52" name="comment">'.$this->comment.'</textarea></td></tr>
            <tr><th>更新・削除キー</th><td><input class="pass_word" maxlength="4" size="11" type="text" name="pass_word" value="'.$this->pass_word.'">
                &nbsp;<span class="small">４桁の英数字</span></td></tr>
            <tr><th></th><td class="right"><input type="submit" name="insertchk" value="  確認  ">
                              <input type="reset" name="cancel" value="  もとに戻す  "></td></tr>
            </table>
          </form>
        </div>
    <!--(3:コメント入力 END)-->';
  }

  /***************************/
  //テキストパーツ
  /***************************/
  function htmlSpanRed($val)
  {
    return '<span><font color="red">'.$val.'</font></span><br>';
  }

  /***************************/
  //ボタン
  /***************************/
  function htmlButtonType($val ='')
  {
    switch ($val)
    {
      case  '1':
        $url1 = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php';
        $ret = '<input type="button" value="  戻る  " onclick="location.href=\''.$url1.'\'">';
        break;
      case  '2':
        $url2 = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/selectgroup.php';
        $url2 .= '?board_id='.$this->board_id;
        $ret = '<input type="button" value="  戻る  " onclick="location.href=\''.$url2.'\'">';
        break;
      default:
        $ret = '<input type="button" value="  戻る  " onclick="history.back();"><br>';
        break;
    }
    return $ret;
  }

  /***************************/
  //メッセージ
  /***************************/
  function htmlMessage()
  {
    $ret = '
      <!--(5:メッセージ表示 START)-->
          <div class="group10">
            <div id="msg">
            <p>'.$this->msg.'</p><br>'.
            $this->button.'
            </div>
          </div>
      <!--(5:メッセージ表示 END)-->';
    return $ret;
  }

  /***************************/
  //エラーメッセージ
  /***************************/
  function htmlErrMessage()
  {
    $ret = '
      <!--(5:メッセージ表示 START)-->
          <div class="group10">
            <div id="msg">'.
            $this->htmlSpanRed($this->msg).'<br>'.
            $this->button.'
            </div>
          </div>
      <!--(5:メッセージ表示 END)-->';
    return $ret;
  }

  /***************************/
  //コメント入力確認
  /***************************/
  function htmlCommentCheck()
  {
    $ret = 
    '<!--(4:コメント確認 START)-->
        <div class="group0">
          <form action="" method="post">
            <table id="newdatacheck">
            <tr><th>名前</th><td>'.$this->handlename.'</td></tr>
            <tr><th>タイトル</th><td>'.$this->title.'</td></tr>
            <tr><th>メッセージ</th><td>'.nl2br($this->comment).'</td></tr>
            <tr><th>更新・削除キー</th><td>'.$this->pass_word.'</td></tr>
            <tr><th></th><td class="right"><input type="submit" name="insert" value="  書込み  ">
                              <input type="button" value="  戻る   " onclick="history.back();"></td></tr>
            </table>
            <input type="hidden" name="handlename" value="'.$this->handlename.'">
            <input type="hidden" name="title" value="'.$this->title.'">
            <input type="hidden" name="comment" value="'.$this->comment.'">
            <input type="hidden" name="pass_word" value="'.$this->pass_word.'">
          </form>
        </div>
    <!--(4:コメント確認 END)-->';
    return $ret;
  }

  /***************************/
  //グループ一覧
  /***************************/
  function htmlGroupView($GroupView, $SubGroupView)
  {
    $ret = '
    <!--(1:グループ一覧 START)-->
        <div class="group0">';
    $ret .= $GroupView;
    $ret .= $SubGroupView;
    $ret .= '
      </div>
      <!--(1:グループ一覧 END)-->';
    return $ret;
  }

  /***************************/
  //グループ一覧
  /***************************/
  function htmlGroupViewFirst()
  {
    $ret = $this->htmlGroupViewComment();
    $ret .= '
          <right><!--btn:start-->
          <div class="button0">';
    $ret .= $this->htmlGroupViewButton();
    $ret .= '
          </div>
          </right><!--btn:end-->';
    return $ret;
  }

  /***************************/
  //グループ一覧パーツ
  /***************************/
  function htmlGroupViewComment()
  {
    return '
      <div class="title_name">'.$this->title.'  ---  '.$this->handlename.'</div>
      <div class="time">'.$this->up_date.'</div>
      <div class="comment">'.nl2br($this->comment).'</div>';
  }

  /***************************/
  //グループ一覧パーツ
  /***************************/
  function htmlGroupViewButton()
  {
    return '
      <div class="btn1">
        <form action="'.$this->urlfile.'?type=1&comment_id='.$this->comment_id.'&board_id='.$this->board_id.'&cnt='.$this->cnt.'" method="post"><input type="submit" name="update" value=" 編集 "></form>
      </div>
      <div class="btn2">
        <form action="'.$this->urlfile.'?type=2&comment_id='.$this->comment_id.'&board_id='.$this->board_id.'&cnt='.$this->cnt.'" method="post"><input type="submit" name="delete" value=" 削除 "></form>
      </div>';
  }

  /***************************/
  //サブグループ一覧
  /***************************/
  function htmlSubGroupView()
  {
    $ret = '
    <!--(1-1:サブグループ一覧 START)-->
          <right>
          <div class="group1">';
    $ret .= $this->htmlGroupViewComment();
    $ret .= '
          <right><!--btn:start-->
            <div class="button1">';
    $ret .= $this->htmlGroupViewButton();
    $ret .= '
            </div>
          </right><!--btn:end-->
          </div>
          </right>
    <!--(1-1:サブグループ一覧 END)-->';
    return $ret;
  }

  /***************************/
  //更新・削除キー確認
  /***************************/
  function htmlKeyCheck()
  {
    return '
      <!--(6:キー確認 START)-->
          <div class="group10">
            <div id="keycheck">
            <form action="" method="post">
            更新・削除キー　<input class="pass_word" size="11" type="text" name="pass_word">
            <input type="submit" name="keycheck" value="  確認  ">
            </form>
            <br>
            <input type="button" value="  戻る  " onclick="history.back();"><br>
            </div>
          </div>
      <!--(6:キー確認 END)-->';
  }
}
