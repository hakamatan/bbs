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
        'handlename'=>'名前',
        'title'=>'タイトル',
        'comment'=>'メッセージ',
        'pass_word'=>'更新・削除キー',
        'admin_id'=>'管理者ＩＤ',
        'admin_pass_word' =>'パスワード'
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
          break;
        case 'title':
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          break;
        case 'comment';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          break;
        case 'pass_word';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          $ret.= $this->LengthCheck($value, $this->itemnamearray[$key], 4);
          $ret.= $this->AlphaNumeralCheck($value, $this->itemnamearray[$key]);
          break;
        case 'admin_id';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          break;
        case 'admin_pass_word';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          $ret.= $this->LengthCheck($value, $this->itemnamearray[$key], 4);
          $ret.= $this->AlphaNumeralCheck($value, $this->itemnamearray[$key]);
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
    $db = new DBClass();
    $dt = $db->GetAdminInfo($admin_id);
    $dr_admin_id = null;
    $dr_admin_pass_word = null;
    foreach ($dt as $dr)
    {
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

    if($admin_id == $dr_admin_id)
    {
      return '入力されたＩＤは使えません。'.'<br>';
    }
    return $ret;
  }

  /******************************/
  //  表示スタートデータ行取得
  /******************************/
  function GetStartRow($page, $pagelimit)
  {
    return (1 == $page) ? 0 : (intval($page) - 1) * $pagelimit;
  }
  
  /******************************/
  //  表示エンドデータ行取得
  /******************************/
  function GetEndRow($page, $pagelimit, $allcount)
  {
    $ans = $page * $pagelimit;
    return ($ans > $allcount) ? $allcount : $ans;
  }
  
  /******************************/
  //  表示全頁数取得
  /******************************/
  function GetAllPage($allcount, $pagelimit)
  {
    return ($allcount % $pagelimit > 0) ? floor($allcount / $pagelimit) + 1 : floor($allcount / $pagelimit);
  }

  /*****************************/
  //  セッションスタート
  /*****************************/
  function SessionStart()
  {
    //セッション
    session_cache_limiter('private, must-revalidate');
    session_start();
  }

  /*****************************/
  //  セッション破棄
  /*****************************/
  function SessionDestroy()
  {
    $_SESSION = array();
    session_destroy();
  }

  /*****************************/
  //  ログインチェック
  /*****************************/
  function CheckLogin()
  {
    if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_pass_word']))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  /*****************************/
  //  １頁表示件数取得
  /*****************************/
  function GetPageLimit()
  {
    $db = new DBClass();
    return $this->CheckLogin() ? $_SESSION['limitpageline'] : $db->pagelimit;
  }

  /*****************************/
  //  カラー取得
  /*****************************/
  function GetColor()
  {
/*    if($this->CheckLogin())
    {
      $ret = array($_SESSION['comment_bk_color'], $_SESSION['comment_viewbk_color']);
    } 
    else
    {
      $ret  = null;
    }*/
    $ret = $this->CheckLogin() ? array($_SESSION['comment_bk_color'], $_SESSION['comment_viewbk_color']) : null;
    return $ret;
 }

  /*****************************/
  //  セットセッション
  /*****************************/
  function SetSession($item)
  {
    foreach ($item as $key => $value)
    {
      switch ($key)
      {
        case 'admin_id':
          $_SESSION['admin_id'] = $value;
          break;
        case 'admin_pass_word':
          $_SESSION['admin_pass_word'] = $value;
          break;
        case 'limitpageline';
          $_SESSION['limitpageline'] = $value;
          break;
        case 'comment_bk_color';
          $_SESSION['comment_bk_color'] = $value;
          break;
        case 'comment_viewbk_color';
          $_SESSION['comment_viewbk_color'] = $value;
          break;

        default:
          break;
      }
    }
  }

  /*****************************/
  //  セッション取得
  /*****************************/
  function GetSession()
  {
    $data =  array('admin_id'=>$_SESSION['admin_id'], 'admin_pass_word'=>$_SESSION['admin_pass_word'], 'limitpageline'=>$_SESSION['limitpageline'], 'comment_bk_color'=> $_SESSION['comment_bk_color'], 'comment_viewbk_color'=>$_SESSION['comment_viewbk_color']);
    return $data;
  }
}
?>
