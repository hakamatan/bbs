<?php
  /******************************/
  /*  入力確認画面  */
  /******************************/
  require_once('hedbottom.php');
  require_once('AddView.php');
  require_once('InputCheck.php');
  require_once('DataSave.php');

  $handlename = $_POST['handlename'];
  $title = $_POST['title'];
  $contents = $_POST['contents'];
  $pass_word = $_POST['pass_word'];

  if(isset($_POST['addsub']))
  {
    $cDcc= new DataCheckClass();
    $msg= $cDcc->InputDataCheck($handlename, $title, $contents, $pass_word);
    if(strlen($msg)>0)
    {
      $msgtype = '1';
      include('Msg.php');    // 失敗画面表示
    }

    
    $cVc = new ViewClass();
    $view = $cVc->DisplayInputNewCheck($handlename, $title, $contents, $pass_word);
    //ページヘッダ
    echo htmlheader('入力確認');
    //インプット
    echo $view;
    //ページフッタ
    echo htmlfooter();
    return;
  }
?>