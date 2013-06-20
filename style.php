<?php 
  header('Content-Type: text/css; charset=utf-8');
?>
@charset "UTF-8";
<?php
  //セッション
  session_cache_limiter('private, must-revalidate');
  session_start();
  
  if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_pass_word']))
  {
     $bk_color = $_SESSION['board_backcolor'];
     $viewbk_color = $_SESSION['comment_backcolor'];
     $subgroup_color = $_SESSION['subcomment_backcolor'];
     $body_color = $_SESSION['body_backcolor'];
     $main_bk_color = $_SESSION['commentboard_backcolor'];
     $title_color = $_SESSION['titel_backcolor'];
  }
  else
  {
     $bk_color = '#40e0d0';
     $viewbk_color = '#b0e0e6';
     $subgroup_color = '#add8e6';
     $body_color = '#ffffff';
     $main_bk_color = '#48d1cc';
     $title_color = '#1e90ff';
  }
  
/*  require_once('DataCheckClass.php');

  $dc = new DataCheckClass();
  //セッション
  $dc->SessionStart();
  $item = array('board_backcolor'=>$bk_color ,'comment_backcolor'=>$viewbk_color, 'subcomment_backcolor'=>$subgroup_color, 'body_backcolor'=>$body_color, 'commentboard_backcolor'=>$main_bk_color, 'titel_backcolor'=>$title_color);
  $dc->GetColor($item);
*/
?>
* {
  padding:0px;
  margin:0px;
  font-size:14px;
}

body {
  width:800px;
  margin-right:auto;
  margin-left:auto;
  background-color:<?php echo $body_color ?>;
}
#maintitle {
  font-size:15pt;
  width:800px;
  font-weight:bold;
  background-color:<?php echo $title_color ?>;
  border-top:2px solid #888888;
  border-bottom:2px solid #888888;
  margin-top:10px;
  padding-top: 5px;
  padding-bottom: 5px;
}

#homelnk {
  background-color:<?php echo $main_bk_color ?>;
  padding-top: 5px;
  text-align:right;
}

#homelnk ul{
  list-style-type:none;
}

#homelnk li{
  display:inline;
}

#pagetitle {
  background-color:<?php echo $main_bk_color ?>;
  /*border-bottom: 1px solid #cccccc;*/
  padding-top: 5px;
  padding-bottom: 5px;
}

#pageinfo {
  background-color:<?php echo $main_bk_color ?>;
  padding-top: 5px;
  padding-bottom: 5px;
}

#maincontents {
  word-wrap:break-word;
  width:800px;
  margin-right:auto;
  margin-left:auto;
  background-color:<?php echo $main_bk_color ?>;
}
#contents {
  word-wrap:break-word;
  width:800px;
  margin-right:auto;
  margin-left:auto;
  padding-top: 5px;
  padding-bottom: 5px;
  background-color:<?php echo $main_bk_color ?>;
}
.group10 {
  word-wrap:break-word;
  width:600px;
  margin-top:10px;
  margin-bottom:10px;;  
  margin-right:auto;
  margin-left:auto;  
  background-color:<?php echo $main_bk_color ?>;
  padding:5px;
  clear:both;
}
.group0 {
  word-wrap:break-word;
  width:600px;
  margin-top:10px;
  margin-bottom:10px;
  margin-right:auto;
  margin-left:auto;
  /*background-color:#BDB76B;*/
  background-color:<?php echo $bk_color ?>;
  padding:5px;
  border: 1px solid #000000;
  clear:both;
}
.group1 {
  word-wrap:break-word;
  width:590px;
  margin-right:auto;
  margin-left:10px;  
  background-color:<?php echo $subgroup_color ?>;
  padding-top:5px;
  padding-bottom:5px;
  border-top: 1px solid #000000;
  clear:both;
}
.time {
  text-align:right;
  color:#0000FF;
}
.title_name {
  font-weight:bold;
  text-align:left;
  color:#8B008B;
}
.comment {
  text-align:left;
}
.group2 {
  word-wrap:break-word;
  width:700px;
  margin-top:10px;
  margin-bottom:10px;;  
  margin-right:auto;
  margin-left:auto;  
  background-color:<?php echo $viewbk_color ?>;
  padding5px;
}

.group2 #titlegrp{
  word-break:break-all;
  word-wrap:break-word;
  width:700px;
  overflow: auto;

}

.group2 #titlegrp th{
  border: 1px solid #000000;
  padding:3px;
}

.group2 #titlegrp td{
  border: 1px solid #000000;
  padding:3px;
}

.small {
  font-size:12px;
  color:#0000FF;
}

#newdata th{
  text-align:left;
}
#newdata td{
  text-align:left;
}

#newdata .right{
  text-align:right;
}

#newdata .top{
  vertical-align:top;
}

.right{
  text-align:right;
}
.left{
  text-align:left;
}

#newdatacheck {
  word-break:break-all;
  word-wrap:break-word;
  width:600px;
  overflow: auto;
}

#newdatacheck td{
  width:430px;
  border: 1px solid #000000;
  padding:3px;
  word-wrap:break-word;

}
#newdatacheck th{
  text-align:left;
  word-wrap:break-word;
}

#newdatacheck td{
  text-align:left;
  word-wrap:break-word;
}

#newdatacheck td.right{
  text-align:right;
  border:none;
  word-wrap:break-word;
}

.button0{
  width:120px;
  /*margin-right:auto;*/
  margin-left:470px; 
}
.button1{
  width:120px;
  /*margin-right:auto;*/
  margin-left:460px;  
}
.btn1{
  float:left;
}
.pass_word {
  ime-mode:inactive;
}
.jpn {
  ime-mode:active;
}

#admin table th, td{
  text-align:left;
}

#adminsetting th {
  text-align:left;
}

