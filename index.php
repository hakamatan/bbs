
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

<head>
	<meta http-equiv="Contrnt-Type" content="text/html; charset=UTF-8" />
	<title>130424MySQLへの接続テスト</title>
</head>
<body>
<center>
	<div id="message board"></div>
		<h1>掲示板</h1>
		<div id="messag">
			<form action="insert.php" method="post">
				<table bgcolor="#66bb00">
					<tr><td width="100">タイトル</td><td><input type="text" name="title" size="50"></td></tr>
					<tr><td width="100" valign="top">メッセージ</td><td></td></tr>
					<tr><td colspan="2"><textarea name="contents" cols="50" rows="3" wrap="soft"></textarea></td></tr>
					<tr align="center"><td colspan="2"><input type="submit"value="書込み" size="20">
																							<input type="reset" value="リセット"></td></tr>
					<tr align="right"><td colspan="2"><input type="text" name="delno" size="5"><input type="submit"value="削除" size="20"></td></tr>
				</table>
			</form>
		</div>
	</div>
<?php
	$dsn = 'mysql:dbname=training01;charset=utf8;host=127.0.0.1';
	$user = 'hakamata';
	$password = 'nami';

	try
	{
		echo '<table><th>id</th><th>タイトル</th><th>内容</th>';
		$dbh = new PDO($dsn, $user, $password);

		$sql = 'select board.id,title,contents from board left join comment on board.id=comment.board_id';
		foreach ($dbh->query($sql) as $row)
		{
			echo '<tr><td>'.$row['id'].'</td>'.
			'<td>'.$row['title'].'</td>'.
			 '<td>'.$row['contents'].'</td></tr>';
   	}
		echo '</table>';
	}
	catch (PDOException $e)
	{
    print('Error:'.$e->getMessage());
    die();
	}
?>

</center>
</body>
</html>
