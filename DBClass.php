<?php
  /******************************/
  //DBクラス
  /******************************/
require_once('ViewClass.php');

class DBClass
{
  private $dbdsn = 'mysql:dbname=training01;charset=utf8;host=127.0.0.1';
  private $dbuser = 'hakamata';
  private $dbpassword = 'nami';
  private $dbh;
  
  //プロパティ
  public $title;
  public $board_id;
  public $comment;
  public $handlename;
  public $pass_word;
  public $comment_id;
  
  private $sql = '';
  private $sql_param = '';

  /******************************/
  //接続
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
  //切断
  /******************************/
  function DbClose()
  {
    $this->dbh = null;
  }

  /******************************/
  //  異常終了
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
  //  エラー
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
  //データ抽出
  /******************************/
  function GetSelectSql($val, $tbl, $where)
  {
    return sprintf("select %s from %s %s", $val, $tbl, $where);
  }

  /******************************/
  //データ抽出
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
  //実行
  /******************************/
  function DataQuery($sql)
  {
    return $this->dbh->query($sql);
  }

  /******************************/
  //データ追加
  /******************************/
  function GetInsertSql($tbl, $item, $val)
  {
    return sprintf("insert into %s (%s) values (%s)", $tbl, $item, $val);
  }

  /******************************/
  //データ更新
  /******************************/
  function GetUpdateSql($tbl, $val, $where)
  {
    return sprintf("update %s set %s where %s", $tbl, $val, $where);
  }

  /******************************/
  //データ削除
  /******************************/
  function GetDeleteSql($tbl, $where)
  {
    return sprintf("delete from %s where %s", $tbl,  $where);
  }

  /******************************/
  //データ追加
  /******************************/
  function InsertData()
  {
    if(!$this->DataExecute($this->sql, $this->sql_param))
    {
      $this->Err('データ追加できませんでした。');
    }
  }

  /******************************/
  //データ更新
  /******************************/
  function UpdateData()
  {
    if(!$this->DataExecute($this->sql, $this->sql_param))
    {
      $this->Err('データ更新できませんでした。');
    }
  }

  /******************************/
  //データ削除
  /******************************/
  function DeleteData()
  {
    if(!$this->DataExecute($this->sql, $this->sql_param))
    {
      $this->Err('データ削除できませんでした。');
    }
  }

  /******************************/
  //実行
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
  //新規コメント追加
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
      $item = "board_id, contents, name, pass_word, title";
      $val = ":board_id, :contents, :handlename, :pass_word, :title";
      $this->sql = $this->GetInsertSql("comment", $item, $val);
      $this->sql_param = array('board_id' => $board_id, 'contents' => $this->comment, 'handlename' => $this->handlename, 'pass_word' => $this->pass_word, 'title' => $this->title);
      $this->InsertData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //新規コメント追加
  /******************************/
  function AddCommentReturn()
  {
    try
    {
      $this->DBOpen();
      
      //コメントデータ追加
      $item = "board_id, contents, name, pass_word, title";
      $val = ":board_id, :contents, :handlename, :pass_word, :title";
      $this->sql = $this->GetInsertSql("comment", $item, $val);
      $this->sql_param = array('board_id' => $this->board_id, 'contents' => $this->comment, 'handlename' => $this->handlename, 'pass_word' => $this->pass_word, 'title' => $this->title);
      $this->InsertData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //新規管理者情報追加
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
      $this->sql_param = array('admin_id' => $this->admin_id, 'admin_pass_word' => $this->admin_pass_word);
      $this->InsertData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //コメント更新
  /******************************/
  function EditComment($comment_id)
  {
    try
    {
      $this->DBOpen();
      $val = "contents = :contents, name = :handlename, pass_word = :pass_word, title = :title";
      $where = "id = :comment_id";

      $this->sql = $this->GetUpdateSql("comment", $val, $where);
      //print '▲$sql-->'.$sql.';<br>';
      $this->sql_param = array('comment_id' => $comment_id, 'contents' => $this->comment, 'pass_word' => $this->pass_word, 'title' => $this->title, 'handlename' => $this->handlename,);
      //print '▲up$sql-->'.$sql.';<br>'.
      $this->InsertData();

      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }
  /******************************/
  //コメント削除
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
  //  コメント・ボード削除
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
/*
      //コメント削除
      $this->sql = $this->GetDeleteSql("comment", "board_id = :board_id");
      $this->sql_param = array('board_id' => $board_id);
      $this->DeleteData();
*/
      $this->DbClose();
  	}
  	catch (PDOException $e)
  	{//異常終了
      $this->ErrException($e->getMessage());
    }
  }

  /******************************/
  //タイトル一覧
  /******************************/
  function GetTitleView()
  {
    try
    {
      $this->DBOpen();
      $val = "b.title, b.id as board_id, c.name as handlename, b.created_at as add_date, c.created_at as up_date, c.title as subject";
      $tbl = " board as b left join (select min( board_id ) as board_id, max(created_at) as created_at, name, title from comment group by board_id) as c on b.id = c.board_id";
      $where = "order by b.id desc";
      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      //print '★->'.$sql.';<br>';
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
  //  グループ一覧
  /******************************/
  function GetGroupView($board_id)
  {
    try
    {
      $this->DBOpen();
      $val = "c.id as comment_id, c.title as title, c.name as handlename, c.created_at as up_date, c.contents as comment, b.created_at as add_date, c.board_id, c.pass_word";
      $tbl = "comment as c join board as b on c.board_id = b.id";
      $where = "where c.board_id = ".$board_id." order by c.id";
      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      //print '●->'.$sql.';<br>';
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
  //  編集コメント抽出
  /******************************/
  function GetComment($comment_id)
  {
    try
    {
      $this->DBOpen();

      $val = "id as comment_id, board_id, contents as comment, created_at as up_date, name as handlename, pass_word, title";
      $tbl = "comment";
      $where = "where id = ".$comment_id;
      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      // print $sql;
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
  //  管理者情報抽出
  /******************************/
  function GetAdminInfo($admin_id)
  {
    try
    {
      $this->DBOpen();

      $val = "id, admin_id , admin_pass_word, comment_bk_color, title_bk_color";
      $tbl = "user";
      $where = "where admin_id = '".$admin_id."'";
      $this->sql = $this->GetSelectSql($val, $tbl, $where);
      // print $sql;
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