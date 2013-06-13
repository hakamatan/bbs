<?php
/*************************************************/
//  ViewClassクラス
/*************************************************/
class ViewClass
{
  /****************************/
  //  ページタイトル
  /****************************/
  public $pagetitlearray = array(
        'add'=>'コメント入力',
        'edit'=>'コメント修正',
        'exception'=>'エラー：異常終了',
        'error'=>'エラー',
        'insert'=>'追加処理',
        'update'=>'更新処理',
        'delete'=>'削除処理',
        'inputcheck'=>'コメント入力確認',
        'adminlogin'=>'管理者ログイン',
        'adminsetting'=>'管理者設定',
        'keycheck'=>'更新・削除キーチェック');
  
  /****************************/
  //  ファイル
  /****************************/
  public $urlarray = array(
        'home'=>'index.php',
        'add'=>'dataupdate.php?type=1',
        'returnadd'=>'dataupdate.php?type=2&board_id=%s',
        'edit'=>'dataupdate.php?type=3&board_id=%s&comment_id=%s',
        'del'=>'dataupdate.php?board_id=%s&comment_id=%s,&cnt=%s',
        'grp_add'=>'selectgroup.php?board_id=%s',
        'grp_edit'=>'selectgroup.php?board_id=%s&comment_id=%s&cnt=%s',
        'admin'=>'admin.php');

  /****************************/
  //  メッセージ
  /****************************/
  public $msgarray = array(
        'ok'=>'正常に処理されました。');

  /****************************/
  //  プロパティ
  /****************************/
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
  public $admin_id;
  public $admin_pass_word;
  public $button_name;

  /***************************/
  //  ページ
  /***************************/
  function htmlView()
  {
    $ret = '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $ret .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">';
    $ret .= '
    <head>
    	<meta http-equiv="Contrnt-Type" content="text/html; charset=UTF-8" />
      <link href="style.php" rel="stylesheet" type="text/css" />
    	<title>掲示板入門編</title>
    </head>';
    $ret .= '
    <body>
    <center>
    <div id="maincontents">
      <br>
      <div id="maintitle"><h3>■　掲示板　■</h3></div>
      <div id="homelnk"><ul><li><a href="./">HOME</a></li>&nbsp;|&nbsp;<li><a href="admin.php">管理者画面</a></li></ul></div>
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
  //  タイトル一覧
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
  //  タイトル一覧本体
  /***************************/
  function htmlTitleViewBody()
  {
    $urlfile = sprintf($this->urlarray['grp_add'], $this->board_id);
    return '
          <tr><td class="left"><a href="'.$urlfile.'">'.$this->title.'</a></td>
              <td class="left">'.$this->handlename.'</td>
              <td>'.$this->add_date.'</td>
              <td>'.$this->up_date.'</td>
          </tr>';
  }

  /***************************/
  //  コメント入力
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
  //  コメント入力
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
  //  テキストパーツ
  /***************************/
  function htmlSpanRed($val)
  {
    return '<span><font color="red">'.$val.'</font></span><br>';
  }

  /***************************/
  //  ボタン
  /***************************/
  function htmlButtonType($val ='')
  {
    switch ($val)
    {
      case  'ok':
        $ret = '<form action="'.$this->urlfile.'" method="post">';
        $ret .= '<input type="submit" name="back" value="  戻る  ">';
        $ret .= '</form>';
        break;
      case  'yesno':
        $ret = '<form action="'.$this->urlfile[0].'" method="post">';
        $ret .= '<input type="submit" name="yes" value="  はい  ">';
        $ret .= '</form>';
        $ret .= '<form action="'.$this->urlfile[1].'" method="post">';
        $ret .= '<input type="submit" name="no" value="  やめる  ">';
        $ret .= '</form>';
        break;
      default:
        $ret = '<input type="button" value="  戻る  " onclick="history.back();"><br>';
        break;
    }
    return $ret;
  }

  /****************************/
  //  ボタン種類
  /****************************/
  public $buttonarray = array('', 'OK', 'YesNO');

  /***************************/
  //  メッセージ
  /***************************/
  function htmlMessage()
  {
    $ret = '
      <!--(5:メッセージ表示 START)-->
          <div class="group10">
            <div id="msg">
            <p>'.$this->msg.'</p><br>';
            switch ($this->button)
            {
              case $this->buttonarray[1]:
                $ret .= '<form action="'.$this->urlfile.'" method="post">';
                $ret .= '<input type="submit" name="back" value="  戻る  ">';
                $ret .= '</form>';
                break;
              case $this->buttonarray[2]:
                $ret .= '<form action="'.$this->urlfile[0].'" method="post">';
                $ret .= '<input type="submit" name="'.$this->button_name[0].'" value="  はい  ">';
                $ret .= '</form>';
                $ret .= '<form action="'.$this->urlfile[1].'" method="post">';
                $ret .= '<input type="submit" name="'.$this->button_name[1].'" value="  やめる  ">';
                $ret .= '</form>';
                break;
              default:
                $ret .= '<input type="button" value="  戻る  " onclick="history.back();"><br>';
                break;
            };
            $ret .= '
            </div>
          </div>
      <!--(5:メッセージ表示 END)-->';
    return $ret;
  }

  /***************************/
  //  エラーメッセージ
  /***************************/
  function htmlErrMessage()
  {
    $ret = '
      <!--(5:メッセージ表示 START)-->
          <div class="group10">
            <div id="msg">'.
            $this->htmlSpanRed($this->msg).'<br>
            <input type="button" value="  戻る  " onclick="history.back();"><br>
            </div>
          </div>
      <!--(5:メッセージ表示 END)-->';
    return $ret;
  }

  /***************************/
  //  コメント入力確認
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
  //  グループ一覧
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
  //  グループ一覧
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
  //  グループ一覧パーツ
  /***************************/
  function htmlGroupViewComment()
  {
    return '
      <div class="title_name">'.$this->title.'  ---  '.$this->handlename.'</div>
      <div class="time">'.$this->up_date.'</div>
      <div class="comment">'.nl2br($this->comment).'</div>';
  }

  /***************************/
  //  グループ一覧パーツ
  /***************************/
  function htmlGroupViewButton()
  {
    $ret = '
      <div class="btn1">
        <form action="'.$this->urlfile.'&type=1" method="post"><input type="submit" name="update" value=" 編集 "></form>
      </div>';
    $ret .= '
      <div class="btn2">
        <form action="'.$this->urlfile.'&type=2" method="post"><input type="submit" name="delete" value=" 削除 "></form>
      </div>';
    return $ret;
  }

  /***************************/
  //  サブグループ一覧
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
  //  更新・削除キー確認
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

  /***************************/
  //  管理者画面
  /***************************/
  function htmlAdminCheck()
  {
    return '
    <!--(7:管理画面 START)-->
        <div class="group10">
          <div id="admin">
          <form action="" method="post">
          <table>
          <tr><th>ログインＩＤ</th><td><input type="text" name="admin_id"></td></tr>
          <tr><th>パスワード</th><td><input class="pass_word" maxlength="4" size="11" type="password" name="admin_pass_word">
                    &nbsp;<span class="small">４桁の英数字</span></td></tr>
          </table>
          <div><br><input type="submit" name="add" value="  登録  "><input type="submit" name="check" value="  確認  "></div>
          </form>
          </div>
        </div>
    <!--(7:管理画面 END)-->';
  }

  /***************************/
  //  管理者画面
  /***************************/
  function htmlAdminSetting($bk_color, $viewbk_color)
  {
    //print sprintf("htmlAdminSetting::bk_color=%s, viewbk_color=%s <br>",$bk_color, $viewbk_color);
    return '
    <!--(8:管理画面 色 START)-->
        <div class="group0">
          <div id="adminlogout"><br>
          <form action="" method="post">
          <p><b>'.$this->admin_id.'&nbsp;さん</b></p><br>
          <input type="submit" name="logout" value="  ログアウト  ">
          </form>
          </div><br>
          <div id="adminsetting">
          <form action="" method="post">
          <table>
          <tr><th>掲示板背景カラー</th><td>'.$this->htmlColorRadioButton($this->comcolorarray, "comcolor", $bk_color).'
          <input class="pass_word" type="text" name="free_comcolor" maxlength=7  size="8" value="'.$bk_color.'"></td></tr>
          <tr><th>掲示板一覧カラー</th><td>'.$this->htmlColorRadioButton($this->viewcolor_array, "viewcolor", $viewbk_color).'
          <input class="pass_word" type="text" name="free_viewcolor" maxlength=7  size="8" value="'.$viewbk_color.'"></td></tr>
          </table><br>
          <input type="submit" name="setting" value=" 設定  ">
                            <input type="reset" name="cancel" value="  もとに戻す  ">
          </form>
          </div>
        </div>
    <!--(8:管理画面 色 END)-->';
  }

  /***************************/
  //  掲示板背景カラー
  /***************************/
  private $comcolorarray = array(
        '#eee8aa'=>'■',
        '#48d1cc'=>'■',
        '#e9967a'=>'■',
        '#bc8f8f'=>'■',
        '#ffa500'=>'■',
        ''=>'フリー'
        );

  /***************************/
  //  掲示板一覧カラー
  /***************************/
  private $viewcolor_array = array(
        '#bc8f8f'=>'●',
        '#eee8aa'=>'●',
        '#48d1cc'=>'●',
        '#e9967a'=>'●',
        '#ff9990'=>'●',
        ''=>'フリー'
);

  /***************************/
  //  管理者カラーラジオボタン
  /***************************/
  function htmlColorRadioButton($radio_array, $name, $data)
  {
    $flg = array_key_exists($data, $radio_array) ? '' : 'chk';
    //print 'flg->'.$flg.', data->'.$data.';<br>';

    $ret = "";
    foreach($radio_array as $key => $val)
    {
     // print 'key->'.$key.',,val--->'.$val.';<br>';
      $checked = ($key == $data || $flg != '') ? " checked='checked'" : "";
      $ret .= "<input type='radio' name='{$name}' value='{$key}'{$checked}><font color=\"".$key."\">".$val."</font>";
    }
    return $ret;
  }

}
