<?php
  /******************************/
  /*  入力チェック  */
  /******************************/
class DataCheckClass
{  
  function InputDataCheck($handlename, $title, $comment, $pass_word)
  {
    //未入力チェック
    $ret='';
    $ret.= $this->DataCheckEnp($handlename, '名前');
    $ret.= $this->DataCheckEnp($title, 'タイトル');
    $ret.= $this->DataCheckEnp($comment, 'メッセージ');
    $ret.= $this->DataCheckEnp($pass_word, '更新・削除キー');
    if(strlen($ret)>0)
    {
		  return $ret;
    }
    //桁数チェック
    $ret.= $this->DataCheckLen($pass_word, '更新・削除キー',4);
    if(strlen($ret)>0)
    {
		  return $ret;
    }
    //入力文字チェック
    $ret.= $this->DataCheckNum($pass_word, '更新・削除キー',4);
    if(strlen($ret)>0)
    {
		  return $ret;
    }
		return $ret;
  }
  
  function DataCheckEnp($item,$itemname)
  {
    $ret='';
    if(strlen($item)==0)
    {
      return $ret = $itemname.'&nbsp;を入力してください。<br>';
    }
    return $ret;
  }

  function DataCheckLen($item, $itemname, $len)
  {
    $ret='';
    if(strlen($item)!=$len)
    {
      return $ret = $itemname.'&nbsp;は&nbsp;'.$len.'&nbsp;桁入力してください。<br>';
    }
    return $ret;
  }

  function DataCheckNum($item, $itemname)
  {
    $ret='';
    if (!preg_match("/^[a-zA-Z0-9]+$/",$item))
    {
      return $ret = $itemname.'&nbsp;は&nbsp;'.$len.'半角英数字で入力してください。<br>';
    }
    return $ret;
  }
}
?>
