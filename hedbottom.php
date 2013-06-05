<?php
  /******************************/
  /*  ヘッダー・フッター  */
  /******************************/
  function htmlheader($pagetitle=''){
  //ヘッダー部
  $strret='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="Contrnt-Type" content="text/html; charset=UTF-8" />
  <link href="css/common.css" rel="stylesheet" type="text/css" />
	<title>掲示板入門編</title>
</head>
<body>
  <br>
	<div id="main_contents">
		<div id="maintitle"><h2>掲示板</h2></div>
    <div id="pagetitle"><h3>'.$pagetitle.'</h3></div>
		<div id="contents"><br>';
  return $strret;
} 

function htmlfooter() {
//フッタ部
  $strret='<br></div></div></body></html>';
  return $strret;
}
?>