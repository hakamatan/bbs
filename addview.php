
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

<head>
	<meta http-equiv="Contrnt-Type" content="text/html; charset=UTF-8" />
	<title>掲示板入門編</title>
</head>
<body>
	<div id="message board"></div>
		<h1>掲示板</h1>
		<div id="messag">
			<form action="insert.php" method="post">
				<table bgcolor="#66bb00">
					<tr><td width="150">名前</td><td><input type="text" name="handlename" size="50"></td></tr>
					<tr><td width="150">タイトル</td><td><input type="text" name="title" size="50"></td></tr>
					<tr><td width="150" valign="top">メッセージ</td><td></td></tr>
					<tr><td colspan="2"><textarea name="contents" cols="50" rows="3" wrap="soft"></textarea></td></tr>
					<tr><td width="150" valign="top">更新・削除キー</td><td><input type="text" name="delno" size="5"></td></tr>
					<tr align="center"><td colspan="2" align="right"><input type="submit"value="書込み" size="20">
																							<input type="reset" value="リセット"></td></tr>
				</table>
			</form>
		</div>
	</div>
