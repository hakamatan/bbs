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
        'keycheck'=>'更新・削除キーチェック',
        'group'=>'グループ表示',
        'search'=>'検索結果'
        );
  
  /****************************/
  //  ファイル
  /****************************/
  public $urlarray = array(
        'home'=>'index.php',
        'homepage'=>'index.php?page=%s',
        'search'=>'search.php?word=%s&orand=%s&page=%s',
        'add'=>'dataupdate.php?type=1',
        'returnadd'=>'dataupdate.php?type=2&board_id=%s',
        'edit'=>'dataupdate.php?type=3&board_id=%s&comment_id=%s',
        'del'=>'dataupdate.php?board_id=%s&comment_id=%s&cnt=%s',
        'grp_add'=>'selectgroup.php?board_id=%s&page=%s',
        'grp_edit'=>'selectgroup.php?board_id=%s&comment_id=%s&cnt=%s&page=%s',
        'admin'=>'admin.php');

  /****************************/
  //  メッセージ
  /****************************/
  public $msgarray = array(
        'ok'=>'正常に処理されました。');

  /****************************/
  //  プロパティ
  /****************************/
  public $contents;     //サイトコンテンツ
  public $pagetitle;    //サイトページタイトル
  public $handlename;   //名前
  public $title;        //掲示板タイトル
  public $retitle;      //掲示板タイトル
  public $comment;      //コメント
  public $up_date;      //掲示板書込み日付
  public $add_date;     //掲示板初期書込み日付
  public $pass_word;    //更新・削除キー
  public $msg;          //表示メッセージ
  public $button;       //表示ボタン
  public $board_id;     //ボードID
  public $comment_id;   //コメントID
  public $urlfile;      //表示URL
  public $cnt;          //コメントレコード行
  public $admin_id;     //管理者ID
  public $admin_pass_word;  //管理者パスワード
  public $button_name;  //表示ボタン名
  public $alldata;      //全件数
  public $pageinfo;     //ページ情報
  public $search_word;  //検索ワード
  public $dt;           //読込みデータ
  public $page;         //ページ
  public $allpage;      //全ページ数
  public $startrow;     //開始行
  public $lastrow;      //終了行

  /***************************/
  //  コンストラクタ
  /***************************/
  function __construct()
  {
  }

  /***************************/
  //  ページ
  //
  //  $this->pagetitle:ページタイトル
  //  $this->contents :ページコンテンツ
  //  $this->pageinfo :ページ情報
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
    $ret .= "
    <body>
    <center>
    <div id='maincontents'>
      <div id='maintitle'><h3>■　掲示板　■</h3></div>
      <div id='homelnk'><ul><li><a href='./'>HOME</a></li>&nbsp;|&nbsp;<li><a href='admin.php'>管理者画面</a></li></ul></div>
      <div id='pagetitle'>{$this->pagetitle}</div>
      <!--コンテンツ START -->
      <div id='contents'>";
    $ret .= "{$this->contents}";
    $ret .= "
      </div>
      <!--コンテンツ END -->
      <div id='pageinfo'>{$this->pageinfo}</div>
    </div>
    </center>
    </body>
    </html>";
    return $ret;
  }

  /***************************/
  //  表示ページ
  //
  //  $this->urlfile  :送信先プログラム
  //  $this->page     :現在表示ページ
  //  $this->startrow :開始行
  //  $this->lastrow  :終了行
  //  $this->allpage  :全ページ数
  /***************************/
  function htmlPageInformation()
  {
    if(0 < $this->alldata)
    {
      $info = sprintf('<td>%s 件ありました。現在 [%s - %s] 件を表示しています。 </td>', $this->alldata, $this->startrow, $this->lastrow);
    }
    else
    {
      return '<table><tr><td>該当するデータはありません。</td></tr></table>';
    }

    $top = '<td><a href="'.sprintf($this->urlfile, 1).'">＜＜先頭へ</a></td>';
    $back = '<td><a href="'.sprintf($this->urlfile, $this->page - 1).'">＜前へ&nbsp;</a></td>';
    $next = '<td><a href="'.sprintf($this->urlfile, $this->page + 1).'">&nbsp;次へ＞</a></td>';
    $bottom = '<td><a href="'.sprintf($this->urlfile, $this->allpage).'">最後へ＞＞</a></td>';
    switch ($this->page)
    {
      case 1:
        $info .= (1 == $this->allpage) ? '' : $next.$bottom;
        break;
      case $this->allpage:
        $info .= $top.$back;
        break;
      default:
        $info .= $top.$back.$next.$bottom;
        break;
    }
    $ret = "<table><tr>{$info}</tr></table>";
    return $ret;
  }

  /***************************/
  //  タイトル一覧
  //
  //  $this->dt:一覧データ
  /***************************/
  function htmlTitleView()
  {
    $ret = '
    <!--(2:タイトル一覧 START)-->
        <div class="group2">
          <table id="titlegrp" cellspacing="1px">
          <tr><th>タイトル</th><th>投稿者</th><th>作成日</th><th>最終更新日</th></tr>';
    foreach ($this->dt as $dr)
    {
      $title = $dr['title'] != $dr['subject'] ? $dr['subject'] : $dr['title'];
      $up_date = $dr['add_date'] != $dr['up_date'] ? $dr['up_date'] : '0000-00-00 00:00:00';
      $urlfile = sprintf($this->urlarray['grp_add'], $dr['board_id'], 1);
      $ret .= "
          <tr><td class='left'><a href='{$urlfile}'>{$title}</a></td><td class='left'>{$dr['handlename']}
          </td><td>{$dr['add_date']}</td><td>{$up_date}</td></tr>";
    }
    $ret .= '
          </table>
        </div>
    <!--(2:タイトル一覧 END)-->';
    return $ret;
  }

  /***************************/
  //  コメント入力
  //
  //  $title  :返信メッセージの時使用
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
  //
  //  $this->urlfile    :送信先プログラム
  //  $this->handlename :名前
  //  $this->title      :タイトル
  //  $this->comment    :メッセージ
  //  $this->pass_word  :更新削除キー
  /***************************/
  function htmlCommentInput()
  {
    return
    "<!--(3:コメント入力 START)-->
        <div class='group0'>
          <form action='{$this->urlfile}' method='post'>
            <table id='newdata'>
            <tr><th>名前</th><td><input class='jpn' size='51' type='text' name='handlename' value='{$this->handlename}'></td></tr>
            <tr><th>タイトル</th><td><input class='jpn' size='51' type='text' name='title' value='{$this->title}'></td></tr>
            <tr><th class='top'>メッセージ</th><td><textarea class='jpn' rows='7' cols='52' name='comment'>{$this->comment}</textarea></td></tr>
            <tr><th>更新・削除キー</th><td><input class='pass_word' maxlength='4' size='11' type='text' name='pass_word' value='{$this->pass_word}'>
                &nbsp;<span class='small'>４桁の英数字</span></td></tr>
            <tr><th></th><td class='right'><input type='submit' name='btn_insertchk' value='  確認  '>
                              <input type='reset' name='btn_reset' value='  もとに戻す  '></td></tr>
            </table>
          </form>
        </div>
    <!--(3:コメント入力 END)-->";
  }

  /***************************/
  //  テキストパーツ
  //
  //  $val:文字を赤くする時使用
  /***************************/
  function htmlSpanRed($val)
  {
    return "<span><font color='red'>{$val}</font></span><br>";
  }

  /****************************/
  //  ボタン種類
  /****************************/
  public $buttonarray = array(
          '',
          'OK',
          'YesNO',
          'top'=>'btn_page_top',
          'next'=>'btn_page_next',
          'back'=>'btn_page_back',
          'bottom'=>'btn_page_bottom',
  );
  /***************************/
  //  メッセージ
  //
  //  $this->msg        :表示メッセージ
  //  $this->button     :表示ボタン種類
  //  $this->urlfile    :送信先プログラム
  //  $this->button_name:表示ボタン名
  /***************************/
  function htmlMessage()
  {
    $ret = '
      <!--(5:メッセージ表示 START)-->
          <div class="group10">
            <div id="msg">
            <p>'.$this->msg.'</p><br><div class="button">';
            switch ($this->button)
            {
              case $this->buttonarray[1]:
                $ret .= "<form action='{$this->urlfile}' method='post'>";
                $ret .= "<input type='submit' name='btn_back' value='  戻る  '>";
                $ret .= "</form>";
                break;
              case $this->buttonarray[2]:
                $ret .= "<table><tr><td>";
                $ret .= "<form action='{$this->urlfile[0]}' method='post'>";
                $ret .= "<input type='submit' name='{$this->button_name[0]}' value='  はい  '>";
                $ret .= "</form></td><td>";
                $ret .= "<form action='{$this->urlfile[1]}' method='post'>";
                $ret .= "<input type='submit' name='{$this->button_name[1]}' value='  やめる  '>";
                $ret .= "</form>";
                $ret .= "</td></tr></table>";
                break;
              default:
                $ret .= "<input type='button' value='  戻る  ' onclick='history.back();'><br>";
                break;
            };
            $ret .= '</div>
            </div>
          </div>
      <!--(5:メッセージ表示 END)-->';
    return $ret;
  }

  /***************************/
  //  エラーメッセージ
  //
  //  $this->msg:表示メッセージ
  /***************************/
  function htmlErrMessage()
  {
    return "
      <!--(5:メッセージ表示 START)-->
          <div class='group10'>
            <div id='msg'>{$this->htmlSpanRed($this->msg)}<br>
            <input type='button' value='  戻る  ' onclick='history.back();'><br>
            </div>
          </div>
      <!--(5:メッセージ表示 END)-->";
  }

  /***************************/
  //  コメント入力確認
  //
  //  $this->handlename :名前
  //  $this->title      :タイトル
  //  $this->comment    :メッセージ
  //  $this->pass_word  :更新削除キー
  /***************************/
  function htmlCommentCheck()
  {
    $comment = nl2br($this->comment);
    return 
    "<!--(4:コメント確認 START)-->
        <div class='group0'>
          <form action='' method='post'>
            <table id='newdatacheck'>
            <tr><th>名前</th><td>{$this->handlename}</td></tr>
            <tr><th>タイトル</th><td>{$this->title}</td></tr>
            <tr><th>メッセージ</th><td>{$comment}</td></tr>
            <tr><th>更新・削除キー</th><td>{$this->pass_word}</td></tr>
            <tr><th></th><td class='right'><input type='submit' name='btn_insert' value='  書込み  '>
                              <input type='button' value='  戻る   ' onclick='history.back();'></td></tr>
            </table>
            <input type='hidden' name='handlename' value='{$this->handlename}'>
            <input type='hidden' name='title' value='{$this->title}'>
            <input type='hidden' name='comment' value='{$this->comment}'>
            <input type='hidden' name='pass_word' value='{$this->pass_word}'>
          </form>
        </div>
    <!--(4:コメント確認 END)-->";
  }

  /***************************/
  //  コメントサーチ
  //
  //  $this->board_id:ボードID
  /***************************/
  function htmlCommentSearch()
  {
    $urlfile = sprintf($this->urlarray['grp_add'], $this->board_id, 1);
    return "<a href='{$urlfile}'>グループを表示する</a>";
  }

  /***************************/
  //  グループ一覧
  //
  //  $this->cnt :グループ1件目1、以外2よりスタート
  //  $this->page:表示ページ
  //  $search:検索の時に使用する
  /***************************/
  function htmlGroupView($search = '')
  {
    $ret = '';
    foreach ($this->dt[0] as $dr)
    {
      $this->retitle = $dr['title'];
      $this->SetGroupData($dr, $this->cnt[0]);
      $ret .= "
        <!--(1:グループ一覧 START)-->
        <div class='group0'>
          {$this->htmlGroupViewComment()}
          <right><!--btn:start-->
            <div class='button0'>";
        $ret .= ($search != '') ? $this->htmlCommentSearch() : $this->htmlGroupViewButton();
        $ret .= "
            </div>
          </right><!--btn:end-->";

      if($this->dt[1] != null)
      {
        foreach ($this->dt[1] as $dr)
        {
          $this->SetGroupData($dr, $this->cnt[1]);
          $ret .= "
          <!--(1-1:サブグループ一覧 START)-->
                <right>
                <div class='group1'>
                {$this->htmlGroupViewComment()}
                <right><!--btn:start-->
                  <div class='button1'>
                  {$this->htmlGroupViewButton()}
                  </div>
                </right><!--btn:end-->
                </div>
                </right>
          <!--(1-1:サブグループ一覧 END)-->";
          $this->cnt[1]++;
        }
      }
      $ret .= '
      </div>
      <!--(1:グループ一覧 END)-->';
      $this->cnt[0]++;
    }
    return $ret;
  }

  /***************************/
  //  グループ一覧パーツ
  //
  //  $dr :データ項目
  //  $cnt:カウント
  /***************************/
  function SetGroupData($dr, $cnt)
  {
    $this->board_id = $dr['board_id'];
    $this->comment_id = $dr['comment_id'];
    $this->title = $dr['title'];
    $this->handlename = $dr['handlename'];
    $this->comment = $dr['comment'];
    $this->up_date = $dr['up_date'];
    $this->urlfile = sprintf($this->urlarray['grp_edit'], $this->board_id, $this->comment_id, $cnt, $this->page);
  }
  
  /***************************/
  //  グループ一覧パーツ
  //
  //  $this->title      :タイトル
  //  $this->handlename :名前
  //  $this->up_date    :更新日付
  //  $this->comment    :メッセージ
  /***************************/
  function htmlGroupViewComment()
  {
    $comment = nl2br($this->comment);
    return "
      <div class='title_name'>{$this->title}  ---  {$this->handlename}</div>
      <div class='time'>{$this->up_date}</div>
      <div class='comment'>{$comment}</div>";
  }

  /***************************/
  //  グループ一覧パーツ
  //
  //  $this->urlfile:送信先プログラム
  /***************************/
  function htmlGroupViewButton()
  {
    $ret = "
      <div class='btn1'>
        <form action='{$this->urlfile}&type=1' method='post'><input type='submit' name='btn_update' value=' 編集 '></form>
      </div>";
    $ret .= "
      <div class='btn2'>
        <form action='{$this->urlfile}&type=2' method='post'><input type='submit' name='btn_delete' value=' 削除 '></form>
      </div>";
    return $ret;
  }

  /***************************/
  //  サブグループ一覧
  //
  //  $this->cnt :グループ1件目1、以外2よりスタート
  //  $this->page:表示ページ
  /***************************/
  function htmlSubGroupView()
  {
    $ret = '';
    foreach ($this->dt as $dr)
    {
      $this->comment_id = $dr['comment_id'];
      $this->title = $dr['title'];
      $this->handlename = $dr['handlename'];
      $this->comment = $dr['comment'];
      $this->up_date = $dr['up_date'];
      $this->urlfile = sprintf($this->urlarray['grp_edit'], $this->board_id, $this->comment_id, $this->cnt, $this->page);

      $ret .= '
      <!--(1-1:サブグループ一覧 START)-->
            <right>
            <div class="group1">';
      $ret .= "{$this->htmlGroupViewComment()}";
      $ret .= "
            <right><!--btn:start-->
              <div class='button1'>";
      $ret .= "{$this->htmlGroupViewButton()}";
      $ret .= '
              </div>
            </right><!--btn:end-->
            </div>
            </right>
      <!--(1-1:サブグループ一覧 END)-->';
      $this->cnt++;
    }
    return $ret;
  }

  /***************************/
  //  更新・削除キー確認画面
  /***************************/
  function htmlKeyCheck()
  {
    return '
      <!--(6:キー確認 START)-->
          <div class="group10">
            <div id="keycheck">
            <form action="" method="post">
            更新・削除キー　<input class="pass_word" size="11" type="text" name="pass_word">
            <input type="submit" name="btn_keycheck" value="  確認  ">
            </form>
            <br>
            <input type="button" value="  戻る  " onclick="history.back();"><br>
            </div>
          </div>
      <!--(6:キー確認 END)-->';
  }

  /***************************/
  //  管理者パスワードチェック画面
  /***************************/
  function htmlAdminCheck()
  {
    return '
    <!--(7:管理画面 START)-->
        <div class="group10">
          <div id="admin">
          <form action="" method="post">
          <table>
          <tr><th>ログインＩＤ</th><td><input class="pass_word" type="text" name="admin_id"></td></tr>
          <tr><th>パスワード</th><td><input class="pass_word" maxlength="4" size="11" type="password" name="admin_pass_word">
                    &nbsp;<span class="small">４桁の英数字</span></td></tr>
          </table>
          <div><br><input type="submit" name="btn_insert" value="  登録  "><input type="submit" name="btn_check" value="  確認  "></div>
          </form>
          </div>
        </div>
    <!--(7:管理画面 END)-->';
  }

  /***************************/
  //  プロパティ（管理者画面）
  /***************************/
  public $limitpageline;          //１頁表示件数
  public $board_backcolor;       //背景カラー
  public $comment_backcolor;   //一覧背景カラー
  public $body_backcolor;     //背景カラー
  public $subcomment_backcolor; //一覧サブ背景カラー
  public $commentboard_backcolor;  //掲示板背景カラー
  public $titel_backcolor; //掲示板タイトルカラー

  /***************************/
  //  管理者画面
  //
  //  $this->board_backcolor     :コメント背景カラー
  //  $this->comment_backcolor :コメント一覧背景カラー
  //  $this->limitpageline        :１頁表示件数
  //  $this->body_backcolor   :ボディ背景カラー
  //  $this->subcomment_backcolor :一覧サブ背景カラー
  //  $this->commentboard_backcolor:掲示板背景カラー
  //  $this->titel_backcolor:タイトルカラー
  /***************************/
  function htmlAdminSetting()
  {
    $ret = "
    <!--(8:管理画面 色 START)-->
        <div class='group0'>
          <div id='adminlogout'><br>
          <form action='' method='post'>
          <p><b>{$this->admin_id}&nbsp;さん</b></p><br>
          <input type='submit' name='logout' value='  ログアウト  '>
          </form>
          </div><br>
          <div id='adminsetting'>
          <form action='' method='post'>";
    $ret .= 
          "<table>
          <tr><th>入力部背景カラー</th><td>{$this->htmlColorRadioButton($this->colorarray, 'comcolor', $this->board_backcolor)}
          <input class='pass_word' type='text' name='free_comcolor' maxlength=7  size='8' value='{$this->board_backcolor}'></td></tr>
          <tr><th>一覧表示部カラー</th><td>{$this->htmlColorRadioButton($this->colorarray, 'viewcolor', $this->comment_backcolor)}
          <input class='pass_word' type='text' name='free_viewcolor' maxlength=7  size='8' value='{$this->comment_backcolor}'></td></tr>
          <tr><th>背景カラー</th><td>{$this->htmlColorRadioButton($this->colorarray, 'bodycolor', $this->body_backcolor)}
          <input class='pass_word' type='text' name='free_body_color' maxlength=7  size='8' value='{$this->body_backcolor}'></td></tr>
          <tr><th>掲示板タイトルカラー</th><td>{$this->htmlColorRadioButton($this->colorarray, 'titelcolor', $this->titel_backcolor)}
          <input class='pass_word' type='text' name='free_titel_bk_color' maxlength=7  size='8' value='{$this->titel_backcolor}'></td></tr>
          <tr><th>掲示板背景カラー</th><td>{$this->htmlColorRadioButton($this->colorarray, 'maincolor', $this->commentboard_backcolor)}
          <input class='pass_word' type='text' name='free_main_bk_color' maxlength=7  size='8' value='{$this->commentboard_backcolor}'></td></tr>
          <tr><th>一覧表示サブ部背景カラー</th><td>{$this->htmlColorRadioButton($this->colorarray, 'subcolor', $this->subcomment_backcolor)}
          <input class='pass_word' type='text' name='free_subgroup_color' maxlength=7  size='8' value='{$this->subcomment_backcolor}'></td></tr>
          <tr><th>１頁表示件数</th><td><input class='pass_word' type='text' name='limitpageline' maxlength=2  size='3' value='{$this->limitpageline}'></td></tr>
          </table><br>
          <input type='submit' name='btn_setting' value=' 設定  '>
                            <input type='reset' name='btn_reset' value='  もとに戻す  '>";
    $ret .= '
          </form>
          </div>
        </div>
    <!--(8:管理画面 色 END)-->';
    return $ret;
  }

  /***************************/
  //  掲示板背景カラー
  /***************************/
  private $colorarray = array(
        '#E6E6E6'=>'■',
        '#F5A9E1'=>'■',
        '#D0A9F5'=>'■',
        '#A9BCF5'=>'■',
        '#A9F5F2'=>'■',
        '#A9F5BC'=>'■',
        '#D0F5A9'=>'■',
        '#F3E2A9'=>'■',
        ''=>'フリー'
        );

  /***************************/
  //  管理者カラーラジオボタン
  //
  //  $radio_array:ラジオボタン種類
  //  $name       :ボタン名
  //  $data       :データ
  /***************************/
  function htmlColorRadioButton($radio_array, $name, $data)
  {
    //カラー配列にデータがあるか？
    $flg = array_key_exists($data, $radio_array) ? '' : 'chk';

    $ret = "";
    foreach($radio_array as $key => $val)
    {
      $checked = ($key == $data || $flg != '') ? " checked='checked'" : "";
      $ret .= "<input type='radio' name='{$name}' value='{$key}'{$checked}><font color='{$key}'>{$val}</font>";
    }
    return $ret;
  }

  /***************************/
  //  コメント検索
  //
  //  $this->urlfile    :送信先プログラム
  //  $this->search_word:検索ワード
  /***************************/
  function htmlWordSearch()
  {
    return "
      <!--(6:キー検索 START)-->
          <div class='group10'>
            <div id='comsearch'>
            <form action='{$this->urlfile}' method='post'>
            <b>ワード</b>　<input class='jpn' size='35' type='text' name='search_word' value='{$this->search_word}'>
            <input type='radio' name='orand' value='or' checked>Or
            <input type='radio' name='orand' value='and'>And
            <input type='submit' name='btn_search' value='  検索  '>
            </form>
            </div>
          </div>
      <!--(6:キー検索 END)-->";
  }

}
