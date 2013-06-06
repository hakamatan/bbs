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
  public $contents;
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
  //タイトル一覧
  /******************************/
  function SelectTitleView()
  {
    $sql = 'select board.title, board.id, name, board.created_at, board.up_date from board
            left join comment on board.id=comment.board_id order by id desc';
    return $this->dbh->query($sql);
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
    //トランザクション
    //$dbh->beginTransaction();
    print 'a<br>';
    //ボード追加
    $sql = $this->InsertCol("insert into board (title) values (:title)");
    $sql->bindParam(":title", $this->title);
    $sql->execute();
    print 'b<br>';

    // 最後に生成した ID を取得
    $board_id = $this->dbh->lastInsertId();
    print 'c<br>';

		//コメントデータ追加
		$sql = $this->InsertCol("insert into comment (board_id, contents, name, pass_word) values (:board_id, :contents, :handlename, :pass_word)");
    $sql->bindParam(":board_id", $board_id);
    $sql->bindParam(":contents", $this->contents);
    $sql->bindParam(":handlename", $this->handlename);
    $sql->bindParam(":pass_word", $this->pass_word);
    $sql->execute();
    print 'd<br>';
  }
}
?>