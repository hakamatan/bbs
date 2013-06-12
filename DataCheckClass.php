<?php
/*************************************************/
//  入力チェッククラス
/*************************************************/
class DataCheckClass
{ 
  /******************************/
  //  項目名
  /******************************/
  private $itemnamearray = array(  
        'handlename' => '名前',
        'title' => 'タイトル',
        'comment'=> 'メッセージ',
        'pass_word' => '更新・削除キー',
        'admin_id' => '管理者ＩＤ',
        'admin_pass_word' => 'パスワード',
);

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
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          //echo '-->'.$key.';<br>';
          break;
        case 'title':
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          //echo '-->'.$key.';<br>';
          break;
        case 'comment';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          //echo '-->'.$key.';<br>';
          break;
        case 'pass_word';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          $ret.= $this->LengthCheck($value, $this->itemnamearray[$key], 4);
          $ret.= $this->AlphaNumeralCheck($value, $this->itemnamearray[$key]);
          //echo '-->'.$key.';<br>';
          break;
        case 'admin_id';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          //echo '-->'.$key.';<br>';
          break;
        case 'admin_pass_word';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          $ret.= $this->LengthCheck($value, $this->itemnamearray[$key], 4);
          $ret.= $this->AlphaNumeralCheck($value, $this->itemnamearray[$key]);
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
      return sprintf("%s  を入力してください。<br>", $itemname);
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
      return sprintf("%s は半角文字 %s 桁を入力してください。<br>", $itemname, $len);
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
      return sprintf("%s は 半角英数字で入力してください。<br>", $itemname);
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
    $dr_pass_word = null;
    foreach ($dt as $dr)
    {
      //print $pass_word.','.$dr['pass_word'].';<br>';
      //return $pass_word != $dr['pass_word'] ? false : true;
      $dr_pass_word = $dr['pass_word'];
    }
    if($pass_word != $dr_pass_word)
    {
      return '認証できません。';
    }
  }

  /******************************/
  //  管理者ＩＤチェック
  /******************************/
  function AdminCheck($admin_id, $admin_pass_word, $type = '')
  {
    //print sprintf("!admin_id=%s, admin_pass_word=%s, type =%s <br>", $admin_id, $admin_pass_word, $type);
    $db = new DBClass();
    $dt = $db->GetAdminInfo($admin_id);
    $dr_admin_id = null;
    $dr_admin_pass_word = null;
    foreach ($dt as $dr)
    {
      //print $pass_word.','.$dr['pass_word'].';<br>';
      $dr_admin_id = $dr['admin_id'];
      $dr_admin_pass_word = $dr['admin_pass_word'];
    }

    $ret = '';
    if($type != '')
    {//認証チェック
      if($admin_id != $dr_admin_id)
      {
        return '管理者ＩＤが存在しません。'.'<br>';
      }
      if($admin_pass_word != $dr_admin_pass_word)
      {
        return 'パスワードが違います。'.'<br>';
      }
      return $ret;
    }

    //print sprintf("!admin_id=%s, dr_admin_id=%s <br>", $admin_id, $dr_admin_id);
    if($admin_id == $dr_admin_id)
    {
      return '入力されたＩＤは使えません。'.'<br>';
    }
    return $ret;
  }

}
?>
