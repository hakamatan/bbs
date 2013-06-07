<?php
  /******************************/
  //DBクラス
  /******************************/
class DBClass
{
  public $dbdsn = 'mysql:dbname=training01;charset=utf8;host=127.0.0.1';
  public $dbuser = 'hakamata';
  public $dbpassword = 'nami';
  public $dbh;

  public $title;
  public $board_id;
  public $comment;
  public $handlename;
  public $pass_word;
  public $comment_id;

  /******************************/
  //接続
  /******************************/
  function DbOpen()
  {
    $this->dbh = new PDO($this->dbdsn, $this->dbuser, $this->dbpassword);
  }

  /******************************/
  //切断
  /******************************/
  function DbClose()
  {
    $this->dbh = null;
  }

  /******************************/
  //select
  /******************************/
  function SelectCol($sql)
  {
    return $this->dbh->query($sql);
  }
 
  /******************************/
  //insert
  /******************************/
  function InsertCol($sql)
  {
    $ret = $this->dbh->prepare($sql);
    return $ret;
  }

  /******************************/
  //新規コメント追加
  /******************************/
  function InsertComment()
  {
    //ボード追加
    $sql = $this->InsertCol("insert into board (title) values (:title)");
    $sql->bindParam(":title", $this->title);
    $flag = $sql->execute();
    if ($flag)
    {
      $flag = false;
      // 最後に生成した ID を取得
      $board_id = $this->dbh->lastInsertId();

      //コメントデータ追加
      $sql = $this->InsertCol("insert into comment (board_id, contents, name, pass_word, title) values (:board_id, :contents, :handlename, :pass_word, :title)");
      $sql->bindParam(":board_id", $board_id);
      $sql->bindParam(":contents", $this->comment);
      $sql->bindParam(":handlename", $this->handlename);
      $sql->bindParam(":pass_word", $this->pass_word);
      $sql->bindParam(":title", $this->title);
      $flag = $sql->execute();
    }
  }

  /******************************/
  //コメント更新
  /******************************/
  function UpdateComment()
  {
    //ボード更新
    $sql = $this->InsertCol("update comment set contents = :contents, name = :name, pass_word = : pass_word, title = :title where id = :id");
    $sql->bindParam(":id", $this->comment_id);
    $sql->bindParam(":contents", $this->comment);
    $sql->bindParam(":name", $this->handlename);
    $sql->bindParam(":pass_word", $this->pass_word);
    $sql->bindParam(":title", $this->title);
    $flag = $sql->execute();
  }

  /******************************/
  //コメント更新
  /******************************/
  function DeleteComment()
  {
    //ボード更新
    $sql = $this->InsertCol("delete from comment where id = :id");
    $sql->bindParam(":id", $this->comment_id);
    $flag = $sql->execute();
    if($flag)
    {
      print '--->'.$flag.'<---';
    }
  }


}
?>