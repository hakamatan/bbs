<?php
  /***************************/
  //ViewClassクラス
  /***************************/
class ViewClass
{
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

  /***************************/
  //ページ
  /***************************/
  function htmlView()
  {
    $ret = $this->htmlHeader();
    $ret .= $this->contents;
    $ret .= $this->htmlFooter();
    return $ret;
  }
  /***************************/
  //ヘッダー部
  /***************************/
  function htmlHeader()
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
      <div id="maintitle"><h3>掲示板</h3></div>
      <div id="pagetitle">'.$this->pagetitle.'</div>
      <!--コンテンツ START -->
      <div id="contents">';
    return $ret;
  }

  /***************************/
  //フッダー部
  /***************************/
  function htmlFooter()
  {
    return '
      </div>
      <!--コンテンツ END -->
      <br>
    </div>
    </center>
    </body>
    </html>';
  }

  /***************************/
  //タイトル一覧
  /***************************/
  function htmlTitleView()
  {
    return '
          <tr><td class="left"><a href="selectgroup.php?board_id='.$this->board_id.'">'.$this->title.'</a></td>
              <td class="left">'.$this->handlename.'</td>
              <td>'.$this->add_date.'</td>
              <td>'.$this->up_date.'</td>
          </tr>';
  }

  /***************************/
  //タイトル一覧ヘッダー
  /***************************/
  function htmlTitleViewHeader()
  {
    return '
    <!--(2:タイトル一覧 START)-->
        <div class="group2">
          <table id="titlegrp" cellspacing="1px">
          <tr><th>タイトル</th><th>投稿者</th><th>作成日</th><th>最終更新日</th></tr>';
  }

  /***************************/
  //タイトル一覧フッター
  /***************************/
  function htmlTitleViewFooter()
  {
    return '
          </table>
        </div>
    <!--(2:タイトル一覧 END)-->';
  }

  /***************************/
  //コメント入力
  /***************************/
  function htmlCommentNewInput()
  {
    $this->handlename = '';
    $this->title = '';
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
            <tr><th>名前</th><td><input size="51" type="text" name="handlename" value="'.$this->handlename.'"></td></tr>
            <tr><th>タイトル</th><td><input size="51" type="text" name="title" value="'.$this->title.'"></td></tr>
            <tr><th class="top">メッセージ</th><td><textarea rows="7" cols="52" name="comment">'.nl2br($this->comment).'</textarea></td></tr>
            <tr><th>更新・削除キー</th><td><input size="11" type="text" name="pass_word" value="'.$this->pass_word.'">
                &nbsp;<span class="small">４桁の英数字</span></td></tr>
            <tr><th></th><td class="right"><input type="submit" name="insertchk" value="  確認  ">
                              <input type="reset" name="cancel" value="  クリア  "></td></tr>
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
        $ret = '<input type="button" value=" 戻る " onclick="location.href=\''.$url1.'\'">';
        break;
      case  '2':
        $url2 = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/group.php';
        $ret = '<input type="button" value=" 戻る " onclick="location.href=\''.$url1.'\'">';
        break;
      default:
        $ret = '<input type="button" value=" 戻る " onclick="history.back();"><br>';
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
                              <input type="button" value=" 戻る " onclick="history.back();"></td></tr>
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
  function htmlGroupView()
  {
    $ret = '
    <!--(1:グループ一覧 START)-->
        <div class="group0">';
    $ret .= $this->htmlGroupViewParts1();
    $ret .= '
          <right><!--btn:start-->
          <div class="button0">';
    $ret .= $this->htmlGroupViewParts2();
    $ret .= '
          </div>
          </right><!--btn:end-->';
    return $ret;
  }

  /***************************/
  //グループ一覧パーツ
  /***************************/
  function htmlGroupViewParts1()
  {
    return '
      <div class="title_name">'.$this->title.'  ---  '.$this->handlename.'</div>
      <div class="time">'.$this->up_date.'</div>
      <div class="comment">'.$this->comment.'</div>';
  }

  /***************************/
  //グループ一覧パーツ
  /***************************/
  function htmlGroupViewParts2()
  {
    return '
      <div class="btn1">
        <form action="selectgroup.php?type=1&comment_id='.$this->comment_id.'&board_id='.$this->board_id.'" method="post"><input type="submit" name="update" value=" 編集 "></form>
      </div>
      <div class="btn2">
        <form action="selectgroup.php?type=2&comment_id='.$this->comment_id.'&board_id='.$this->board_id.'" method="post"><input type="submit" name="delete" value=" 削除 "></form>
      </div>';
  }

  /***************************/
  //グループ一覧エンド
  /***************************/
  function htmlGroupViewEnd()
  {
    return '
      </div>
      <!--(1:グループ一覧 END)-->';
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
    $ret .= $this->htmlGroupViewParts1();
    $ret .= '
          <right><!--btn:start-->
            <div class="button1">';
    $ret .= $this->htmlGroupViewParts2();
    $ret .= '
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
            更新・削除キー　<input size="11" type="text" name="pass_word">
            <input type="submit" name="keycheck" value="  確認  ">
            </form>
            </div>
          </div>
      <!--(6:キー確認 END)-->';
  }
}
