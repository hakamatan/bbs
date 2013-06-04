<?php

  include('addview.php');
  require_once('db.php');
	try
	{
		echo '<table><th>id</th><th>タイトル</th><th>内容</th>';
		$dbh = new PDO($dbdsn, $dbuser, $dbpassword);

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

</body>
</html>
