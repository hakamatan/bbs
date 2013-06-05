<?php
  /******************************/
  /*  メッセージ表示  */
  /******************************/

  require_once('hedbottom.php');
  

  if (!isset($msg))
  {
      $msg = "";
  }
  if (!isset($msgtype))
  {
      $msgtype = "";
  }

  list ($pagetitle, $view)  = SetMessage($msgtype, $msg);

  function SetMessage($msgtype, $msg)
  {
    //ひとつ前
    $bk = '<input type="button" value="戻る" onclick="history.back();"><br>';
    //入力画面
    $url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php';
    $home = '<input type="button" value="戻る" onclick="location.href=\''.$url.'\'">';

    switch ($msgtype='')
    {
      case  '2':
        $color = 'red';
        $ptitle = '異常終了';
        $bckbtn=$bk;
        break;
      case  '1':
        $color = 'red';
        $ptitle = 'エラー';
        $bckbtn=$bk;
        break;
      default:
        $color = '#000000';
        $ptitle = '正常終了';
        $bckbtn=$home;
        break;
    }
    $ret = '<span><font color="'.$color.'">'.$ptitle.'</font></span><br>';
    $ret2 = '<span><font color="'.$color.'">'.$msg.'</font></span><br>';
    $ret2 .= '<form>'.$bckbtn.'</form>';
    return array ($ret, $ret2);
  }

    //ページヘッダ
    echo htmlheader($pagetitle);
    //インプット
    echo $view;
    //ページフッタ
    echo htmlfooter();
    return;
?>

