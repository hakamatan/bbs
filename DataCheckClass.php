<?php
  /******************************/
  /*  入力チェック  */
  /******************************/
class DataCheckClass
{ 
  public $itemnamearray = array(  
        'handlename' => '名前',
        'title' => 'タイトル',
        'comment'=> 'メッセージ',
        'pass_word' => '更新・削除キー');

  /******************************/
  //  チェック項目
  /******************************/
  function InputDataCheck($item)
  {
    $ret='';
    foreach ($item as $key => $value)
    {
      switch ($key)
      {
        case 'handlename':
          $ret.= $this->EmptyCheck($value, $this->itemnamearray['handlename']);
          //echo '-->'.$key.';<br>';
          break;
        case 'title':
          $ret.= $this->EmptyCheck($value, $this->itemnamearray['title']);
          //echo '-->'.$key.';<br>';
          break;
        case 'comment';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray['comment']);
          //echo '-->'.$key.';<br>';
          break;
        case 'pass_word';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray['pass_word']);
          $ret.= $this->LengthCheck($value, $this->itemnamearray['pass_word'], 4);
          $ret.= $this->AlphaNumeralCheck($value, $this->itemnamearray['pass_word']);
          //echo '-->'.$key.';<br>';
          break;
        default:
          break;
      }
    }
    return $ret;
  }
  
  /******************************/
  //  未入力チェック
  /******************************/
  function EmptyCheck($item, $itemname)
  {
    $ret='';
    if(strlen($item)==0)
    {
      return $itemname.'&nbsp;を入力してください。<br>';
    }
    return $ret;
  }
  
  /******************************/
  //  桁数チェック
  /******************************/
  function LengthCheck($item, $itemname, $len)
  {
    $ret='';
    if(strlen($item)!=$len)
    {
      return $itemname.'&nbsp;は半角文字&nbsp;'.$len.'&nbsp;桁を入力してください。<br>';
    }
    return $ret;
  }

  /******************************/
  //  英数字チェック
  /******************************/
  function AlphaNumeralCheck($item, $itemname)
  {
    $ret='';
    if (!preg_match("/^[a-zA-Z0-9]+$/",$item))
    {
      return $ret = $itemname.'&nbsp;は 半角英数字で入力してください。<br>';
    }
    return $ret;
  }

  /******************************/
  //  更新・削除キー認証チェック
  /******************************/
  function Pass_WordCheck($pass_word, $comment_id)
  {
    $db = new DBClass();
    $dt = $db->GetComment($comment_id);
    foreach ($dt as $dr)
    {
      print $pass_word.','.$dr['pass_word'].';<br>';
       return $pass_word != $dr['pass_word'] ? false : true;
    }
  }

}
?>
