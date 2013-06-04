<?php
	//DB接続
	$dsn = 'mysql:dbname=training01;host=127.0.0.1';
	$user = 'hakamata';
	$password = 'nami';
	$dbh = new PDO($dsn, $user, $password);
	//
	$title = $_POST['title'];
	$contents = $_POST['contents'];
	$delno = $_POST['delno'];

	try
	{
		if(strlen($delno)>0)
		{
			echo 'delno2->'.$delno.'<br>';
			$sql = 'select count(*) as cnt from board where id='.$delno;
			echo '$sql2->'.$sql.'<br>';
			foreach ($dbh->query($sql) as $row)
			{
				$cnt = $row['cnt'];
   		}
			if($cnt==0){echo 'ナンバーが存在しません'; return;}
			
			$dbh->beginTransaction();//トランザクション
			$sql = 'delete from board where id = :id';
	    $stmt = $dbh->prepare($sql);
	    $flag = $stmt->execute(array(':id' => $delno));

	    if ($flag){
	        print('boardデータの削除に成功しました<br>');
	    }else{
	        print('boardデータの削除に失敗しました<br>');
					$dbh->rollBack();
	    }
			
			$sql = 'delete from comment where board_id = :board_id';
	    $stmt = $dbh->prepare($sql);
	    $flag = $stmt->execute(array(':board_id' => $delno));
	    if ($flag){
	        print('commentデータの削除に成功しました<br>');
	    }else{
	        print('commentデータの削除に失敗しました<br>');
					$dbh->rollBack();
	    }
					$dbh->commit();
					return;
		}


		//未入力チェック
		if(strlen($title)==0 || strlen($title)==0)
		{
			echo '入力してください';
			return;
		}
		//ボード追加
		 echo '---->'.$title.'|<br>';
 		$sql = $dbh->prepare("insert into board (title) value ('".$title."')");
// 		$sql = $dbh->prepare("insert into board (title) value (?)");
		$sql->execute(array($title));
		//ボード最新データ抽出	
		$sql = 'select id from board where created_at = (select max(created_at) from board)';
		foreach ($dbh->query($sql) as $row)
		{
			$board_id = $row['id'];
   	}
		//コメントデータ追加
		$sql = $dbh->prepare("insert into comment (board_id, contents) value (".$board_id.",'".$contents."')");
		$sql->execute(array($board_id, $contents));
	}
	catch (PDOException $e)
	{
    echo 'Error:'.$e->getMessage();
    die();
	}
	$message = '追加されました';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

<head>
	<meta http-equiv="Contrnt-Type" content="text/html; charset=UTF-8" />
	<title>追加確認</title>
</head>
<body>
	<div id="message board"></div>
	<h3>追加確認</h3>
		<div id="messag">
			<?php= $message ?>
			<form action="insert.php" method="post">
				<table border="1" bgcolor="#66bbee">
					<tr><td width="100">タイトル</td><td><?php= $title ?></td></tr>
					<tr><td width="100" valign="top">メッセージ</td><td></td></tr>
					<tr><td colspan="2"><?php= $contents ?></textarea></td></tr>
				</table>
	      <br />
				<Input type=button value="戻る" onClick="javascript:history.go(-1)">
			</form>
		</div>
	</div>
</body>
</html>
