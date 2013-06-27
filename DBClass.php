<?php
/*************************************************/
//  DBクラス
/*************************************************/
require_once('ViewClass.php');

class DBClass
{
  /******************************/
  //  DB定数
  /******************************/
  private $dbdsn = 'mysql:dbname=training01;charset=utf8;host=127.0.0.1';
  private $dbuser = 'hakamata';
  private $dbpassword = 'nami';
  private $dbh;
  
  /******************************/
  //  プロパティ
  /******************************/
  public $title;
  public $board_id;
  public $comment;
  public $handlename;
  public $pass_word;
  public $comment_id;
  public $board_backcolor;
  public $comment_backcolor;
  public $limitpageline;
  public $subcomment_backcolor;
  public $commentboard_backcolor;
  public $titel_backcolor;
  public $body_backcolor;
  public $imagefile;

  private $sql = '';
  private $sql_param = '';

  /******************************/
  //  定数
  /******************************/
  public $pagelimit = 15;
  public $bk_color = '#BDB76B';
  public $viewbk_color = '#FFDEAD';

  /******************************/
  //  接続
  /******************************/
  function DbOpen()
  {
    try
    {
      $this->dbh = new PDO($this->dbdsn, $this->dbuser, $this->dbpassword);
      $this->dbh == null ? false : true;
      if($this->dbh == null)
      {
        $this->Err('データベースに接続できません。');
      }
    }
    catch (PDOException $e)
    {
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  切断
  /******************************/
  function DbClose()
  {
    $this->dbh = null;
  }

  /******************************/
  //  例外エラー
  /******************************/
  function ErrException($e)
  {
    $this->ErrDisplay($e);
  }

  /******************************/
  //  エラー
  /******************************/
  function Err($e)
  {
    $this->ErrDisplay($e, '1');
  }

  /******************************/
  //  エラー処理
  /******************************/
  function ErrDisplay($e, $type = '')
  {
    $this->DbClose(); //DB Close

    $view = new ViewClass();
    $pagetitle = $type != '' ? $view->pagetitlearray['error'] : $view->pagetitlearray['exception'];
    $view->pagetitle = $view->htmlSpanRed($pagetitle);
    $view->msg = $e;
    $view->contents = $view->htmlErrMessage();
    echo $view->htmlView();
    die();
  }

  /******************************/
  //  データ抽出
  /******************************/
  function GetSelectSql($val, $tbl, $where)
  {
    return sprintf("select %s from %s %s", $val, $tbl, $where);
  }

  /******************************/
  //  データ抽出
  /******************************/
  function SelectData()
  {
    $ret = $this->DataQuery($this->sql);
    if(false == $ret)
    {
      $this->Err('データ抽出できませんでした。');
    }
    return $ret;
  }

  /******************************/
  //  実行
  /******************************/
  function DataQuery($sql)
  {
    return $this->dbh->query($sql);
  }

  /******************************/
  //  データ追加コマンド
  /******************************/
  function GetInsertSql($tbl, $item, $val)
  {
    return sprintf("insert into %s (%s) values (%s)", $tbl, $item, $val);
  }

  /******************************/
  //  データ更新コマンド
  /******************************/
  function GetUpdateSql($tbl, $val, $where)
  {
    return sprintf("update %s set %s where %s", $tbl, $val, $where);
  }

  /******************************/
  //  データ削除コマンド
  /******************************/
  function GetDeleteSql($tbl, $where)
  {
    return sprintf("delete from %s where %s", $tbl,  $where);
  }

  /******************************/
  //  データ追加
  /******************************/
  function InsertData()
  {
    if(!$this->DataExecute($this->sql, $this->sql_param))
    {
      $this->Err('データ追加できませんでした。');
    }
  }

  /******************************/
  //  データ更新
  /******************************/
  function UpdateData()
  {
    if(!$this->DataExecute($this->sql, $this->sql_param))
    {
      $this->Err('データ更新できませんでした。');
    }
  }

  /******************************/
  //  データ削除
  /******************************/
  function DeleteData()
  {
    if(!$this->DataExecute($this->sql, $this->sql_param))
    {
      $this->Err('データ削除できませんでした。');
    }
  }

  /******************************/
  //  実行
  /******************************/
  function DataExecute($sql, $sql_param)
  {
    $stmt = $this->dbh->prepare($sql);
    foreach ($sql_param as $key => &$value)
    {
      $stmt->bindParam(':'.$key, $value);
    }
    return $stmt->execute();
/*
    foreach ($sql_param as $key => $value)
    {
      print $key.'=>'.$value.';<br>';
      $stmt->bindValue(':'.$key, $value);
    }
*/
  }

  /******************************/
  //  新規コメント追加
  /******************************/
  function AddComment()
  {
    try
    {
      $this->DBOpen();

      //ボード追加
      $this->sql = $this->GetInsertSql("board", "title", ":title");
      $this->sql_param = array('title' => $this->title);
      $this->InsertData();

      // 最後に生成した ID を取得
      $board_id = $this->dbh->lastInsertId();
      
      //コメントデータ追加
      $item = "board_id, contents, name, pass_word, title, img";
      $val = ":board_id, :contents, :handlename, :pass_word, :title, :img";
      $this->sql = $this->GetInsertSql("comment", $item, $val);
      $this->sql_param = array('board_id' => $board_id, 'contents' => $this->comment, 'handlename' => $this->handlename, 'pass_word' => $this->pass_word, 'title' => $this->title, 'img' => $this->imagefile);
      $this->InsertData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  新規コメント追加（返信時）
  /******************************/
  function AddCommentReturn()
  {
    try
    {
      $this->DBOpen();
      
      //コメントデータ追加
      $item = "board_id, contents, name, pass_word, title, img";
      $val = ":board_id, :contents, :handlename, :pass_word, :title, :img";
      $this->sql = $this->GetInsertSql("comment", $item, $val);
      $this->sql_param = array('board_id' => $this->board_id, 'contents' => $this->comment, 'handlename' => $this->handlename, 'pass_word' => $this->pass_word, 'title' => $this->title, 'img' => $this->imagefile);
      $this->InsertData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  管理者情報追加
  /******************************/
  function AddAdminInfo()
  {
    try
    {
      $this->DBOpen();
      
      //コメントデータ追加
      $item = "admin_id, admin_pass_word";
      $val = ":admin_id, :admin_pass_word";
      $this->sql = $this->GetInsertSql("user", $item, $val);
      $this->sql_param = array('admin_id' => $this->admin_id, 'admin_pass_word' => sha1($this->admin_pass_word));
      $this->InsertData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  コメント更新
  /******************************/
  function EditComment($comment_id)
  {
    try
    {
      $this->DBOpen();
      $val = "contents = :contents, name = :handlename, pass_word = :pass_word, title = :title, img = :img";
      $where = "id = :comment_id";

      $this->sql = $this->GetUpdateSql("comment", $val, $where);
      $this->sql_param = array('comment_id' => $comment_id, 'contents' => $this->comment, 'pass_word' => $this->pass_word, 'title' => $this->title, 'handlename' => $this->handlename, 'img' => $this->imagefile);
      $this->UpdateData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  管理者情報更新
  /******************************/
  function EditAdminInfo($admin_id)
  {
    try
    {
      $this->DBOpen();
      $val = "board_backcolor = :bk_color, comment_backcolor = :viewbk_color, limitpageline = :limitpageline, subcomment_backcolor = :subgroup_color, commentboard_backcolor = :main_bk_color, titel_backcolor = :titel_bk_color, body_backcolor = :body_color";
      $where = "admin_id = :admin_id";

      $this->sql = $this->GetUpdateSql("user", $val, $where);
      $this->sql_param = array('admin_id'=>$admin_id, 'bk_color'=>$this->board_backcolor, 'viewbk_color'=>$this->comment_backcolor, 'limitpageline'=>$this->limitpageline, 'subgroup_color'=>$this->subcomment_backcolor, 'main_bk_color'=>$this->commentboard_backcolor, 'titel_bk_color'=>$this->titel_backcolor, 'body_color'=>$this->body_backcolor);
      $this->UpdateData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  コメント削除
  /******************************/
  function DeleteComment($comment_id)
  {
    try
    {
      $this->DBOpen();

      //コメント1件削除
      $this->sql = $this->GetDeleteSql("comment", "id = :comment_id");
      $this->sql_param = array('comment_id' => $comment_id);
      $this->DeleteData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  ボード削除
  /******************************/
  function DeleteBoard($board_id)
  {
    try
    {
      $this->DBOpen();

      //ボード削除
      $this->sql = $this->GetDeleteSql("board", "id = :board_id");
      $this->sql_param = array('board_id' => $board_id);
      $this->DeleteData();

      //コメント削除
      $this->sql = $this->GetDeleteSql("comment", "board_id = :board_id");
      $this->sql_param = array('board_id' => $board_id);
      $this->DeleteData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  タイトル一覧抽出
  /******************************/
  function GetAllDataCount()
  {
    $this->sql = "select FOUND_ROWS() as count";
    return $this->SelectData();
  }

  /******************************/
  //  タイトル一覧抽出
  /******************************/
  function GetTitleView($startrow, $pagelimit)
  {
    try
    {
      $this->DBOpen();
      
      $val = "SQL_CALC_FOUND_ROWS b.title, b.id as board_id, c.name as handlename, b.created_at as add_date, c.created_at as up_date, c.title as subject";
      $tbl = " board as b left join (select min( board_id ) as board_id, max(created_at) as created_at, name, title from comment group by board_id) as c on b.id = c.board_id";
      $where = "order by c.created_at desc, b.id desc";
      $where .= " limit ".$startrow.",".$pagelimit;
      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      $ret = $this->SelectData();

      $retall = $this->GetAllDataCount();

      $this->DbClose();
      return array($ret, $retall);
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  グループ一覧抽出
  /******************************/
  function GetGroupView($board_id, $startrow, $pagelimit)
  {
    try
    {
      $this->DBOpen();
      
      //親抽出
      $val = "c.id as comment_id, c.title as title, c.name as handlename, c.created_at as up_date, c.contents as comment, b.created_at as add_date, c.board_id, c.pass_word, min(c.id) as minid, c.img";
      $tbl = "comment as c join board as b on c.board_id = b.id";
      $where = "where c.board_id = ".$board_id." order by c.id";
      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      $retfirst = $this->SelectData();
      
      //子データ抽出
      $val = "SQL_CALC_FOUND_ROWS c.id as comment_id, c.title as title, c.name as handlename, c.created_at as up_date, c.contents as comment, b.created_at as add_date, c.board_id, c.pass_word, c.img";
      $tbl = "comment as c join board as b on c.board_id = b.id";
      $where = "where c.board_id = ".$board_id." order by c.id";
      $where .= " limit ".$startrow.",".$pagelimit;
      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      $ret = $this->SelectData();

      //件数
      $retall = $this->GetAllDataCount();

      $this->DbClose();
//      return $ret;
      return array($ret, $retall, $retfirst);
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  編集コメント抽出
  /******************************/
  function GetComment($comment_id, $board_id)
  {
    try
    {
      $this->DBOpen();

      $val = "id as comment_id, board_id, contents as comment, created_at as up_date, name as handlename, pass_word, title, img";
      $tbl = "comment";
      $where = ($comment_id != "") ? "where id = ".$comment_id : "where board_id = ".$board_id;
      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      $ret = $this->SelectData();

      $this->DbClose();
      return $ret;
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //  キーワードコメント抽出
  /******************************/
  function GetCommentView($word, $terms, $startrow, $pagelimit)
  {
    try
    {
      $this->DBOpen();

      $val = "SQL_CALC_FOUND_ROWS id as comment_id, board_id, contents as comment, created_at as up_date, name as handlename, pass_word, title, img";
      $tbl = "comment";

      $cnt = count($word);
      $where = "";
      if(0 < $cnt)
      {//検索ワードあり
        $where .= "where contents LIKE '%".$word[0]."%'";
        for ($i = 1; $i < $cnt; $i++)
        {
          $where .= ($terms == 'or') ? " or contents LIKE '%".$word[$i]."%'" : " and contents LIKE '%".$word[$i]."%'";
        }
      }
      $where .= " limit ".$startrow.",".$pagelimit;

      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      $ret = $this->SelectData();

      $retall = $this->GetAllDataCount();

      $this->DbClose();
      return array($ret, $retall);

    }
    catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }
  
  /******************************/
  //  管理者情報抽出
  /******************************/
  function GetAdminInfo($admin_id)
  {
    try
    {
      $this->DBOpen();

      $val = "admin_id , admin_pass_word, board_backcolor, comment_backcolor, limitpageline, body_backcolor, subcomment_backcolor, commentboard_backcolor, titel_backcolor";
      $tbl = "user";
      $where = "where admin_id = '".$admin_id."'";
      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      $ret = $this->SelectData();

      $this->DbClose();
      return $ret;
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

}
?>