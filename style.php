<?php 
  header('Content-Type: text/css; charset=utf-8');

  //セッション
  session_cache_limiter('private, must-revalidate');
  session_start();

  if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_pass_word']))
  {
     $bk_color = $_SESSION['comment_bk_color'];
     $viewbk_color = $_SESSION['comment_viewbk_color'];
  }
  else
  {
     $bk_color = '#BDB76B';
     $viewbk_color = '#FFDEAD';
  }
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
}
#maintitle {
  font-size:15pt;
  width:800px;
  font-weight:bold;
  background-color: #E6E6FA;
  border-top:2px solid #888888;
  border-bottom:2px solid #888888;
  padding-top: 5px;
  padding-bottom: 5px;
}

#homelnk {
  background-color        : #ffffd9;
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
  background-color        : #ffffd9;
  border-bottom     : 1px solid #cccccc;
  padding-top: 5px;
  padding-bottom: 5px;
}

#pageinfo {
  background-color        : #ffffd9;
  padding-top: 5px;
  padding-bottom: 5px;
}

#maincontents {
  word-wrap:break-word;
  width:800px;
  margin-right:auto;
  margin-left:auto;
  background-color:#FFB6C1;
}
#contents {
  word-wrap:break-word;
  width:800px;
  margin-right:auto;
  margin-left:auto;
  background-color:#7FFF00;
}
.group10 {
  word-wrap:break-word;
  width:600px;
  margin-top:10px;
  margin-bottom:10px;;  
  margin-right:auto;
  margin-left:auto;  
  background-color:#BaBafB;
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
  width:500px;
  margin-right:auto;
  margin-left:100px;  
  background-color:#FFD700;
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
  /*background-color:#FFDEAD;*/
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
  margin-left:480px; 
}
.button1{
  width:120px;
  /*margin-right:auto;*/
  margin-left:380px;  
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

