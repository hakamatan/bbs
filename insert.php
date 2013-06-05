<?php
  /******************************/
  /*  追加処理  */
  /******************************/
  require_once('hedbottom.php');
  require_once('db.php');

  $handlename = $_POST['handlename'];
  $title = $_POST['title'];
  $contents = $_POST['contents'];
  $pass_word = $_POST['pass_word'];

	try
	{
		$dbh = new PDO($dbdsn, $dbuser, $dbpassword);
//    //トランザクション
//    $dbh->beginTransaction();
		//ボード追加
    $sql = $dbh->prepare("insert into board (title) values (:title)");
    $sql->bindParam(":title", $title);
    $sql->execute();

    // 最後に生成した ID を取得
    $board_id = $dbh->lastInsertId();

		//コメントデータ追加
		$sql = $dbh->prepare("insert into comment (board_id, contents, name, pass_word) values (:board_id, :contents, :handlename, :pass_word)");
    $sql->bindParam(":board_id", $board_id);
    $sql->bindParam(":contents", $contents);
    $sql->bindParam(":handlename", $handlename);
    $sql->bindParam(":pass_word", $pass_word);
    $sql->execute();

//    //コミット
//    $dbh->commit();
    // 切断
    $pdo = null;
	}
	catch (PDOException $e)
	{
    $dbh->rollBack();
    // 切断
    $pdo = null;
    $msg= $e->getMessage();
    $msgtype = '9';
    include('Msg.php');
    //die();
	}

  $msg= '追加しました。';
  $msgtype = '';
  include('Msg.php');

?>
