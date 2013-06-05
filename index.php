<?php
  /******************************/
  /*  メイン  */
  /******************************/
  require_once('db.php');
  require_once('hedbottom.php');
  require_once('AddView.php');
	try
	{
    //ここなんとかせねば。
		$dbh = new PDO($dbdsn, $dbuser, $dbpassword);
		$sql = 'select board.id,title,contents from board left join comment on board.id=comment.board_id order by id desc';

		$body = '<table><tbody><tr><th>id</th><th>タイトル</th><th>内容</th></tr>';
		foreach ($dbh->query($sql) as $row)
		{
			$body.= '<tr><td>'.$row['id'].'</td><td>'.$row['title'].'</td><td>'.nl2br($row['contents']).'</td>';
      $body.='<td><form action="passwordchk.php&id=1" method="post"><input type="submit" name="editsub" value="編集"></form></td>';
      $body.='<td><form action="passwordchk.php&id=2" method="post"><input type="submit" name="delsub" value="削除"></form></td></tr>';
   	}
		$body.= '</tbody></table>';

    $cVc = new ViewClass();
    $view = $cVc->DisplayInputNew();

    //ページヘッダ
    echo htmlheader('メイン画面');
    //インプット
    echo $view;
    //ページ本文t
    echo $body;
    //ページフッタ
    echo htmlfooter();

	}
	catch (PDOException $e)
	{
    print('Error:'.$e->getMessage());
    die();
	}
?>
