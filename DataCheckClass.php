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
        'admin_pass_word' =>'パスワード',
        'limitpageline' =>'１頁表示件数'
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
        case 'limitpageline';
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          $ret.= $this->NumeralCheck1($value, $this->itemnamearray[$key]);
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
  //  数字チェック
  /******************************/
  function NumeralCheck1($item, $itemname)
  {
    $ret='';
    if (!is_numeric($item) || 1 >= $item)
    {
      return sprintf("%s は １以上の数字で入力してください。<br>", $itemname);
    }
    return $ret;
  }

  /******************************/
  //  更新・削除キー認証チェック
  /******************************/
  function UpDelKeyCheck($pass_word, $comment_id)
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
      if(sha1($admin_pass_word) != $dr_admin_pass_word)
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
    // セッション変数を全て解除する
    $_SESSION = array();
    // セッションを切断するにはセッションクッキーも削除する。
    // Note: セッション情報だけでなくセッションを破壊する。
    if (isset($_COOKIE[session_name()]))
    {
      setcookie(session_name(), '', time()-42000, '/');
    }
    // 最終的に、セッションを破壊する
    session_destroy();
    
    setcookie('admin_id', '', time()-4200);
  }
  
  /*****************************/
  //  クッキーセット
  /*****************************/
  function SetCookie($admin_id)
  {
    setcookie('admin_id', $admin_id, time()+180);
    print '(SetCookie)'. $admin_id.';';
    print '(SetCookie)'. $_COOKIE['admin_id'].';';
  }
  /*****************************/
  //  ログインチェック
  /*****************************/
  function CheckLogin()
  {
/*    if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_pass_word']))
    {
      return true;
    }
    else
    {
      return false;
    }*/
    print sprintf("(c)admin_id=%s, (s)admin_id=%s, admin_pass_word=%s <br>", $_COOKIE['admin_id'], $_SESSION['admin_id'], $_SESSION['admin_pass_word']);
    if(isset($_COOKIE['admin_id']) || (isset($_SESSION['admin_id']) && isset($_SESSION['admin_pass_word'])))
    {
      return true;
    }
    else
    {
      return false;
    }
    
  }

  /*****************************/
  //  頁表示初期値取得
  /*****************************/
  function GetPageInitialeVlaue(&$startrow, &$allpage, &$allcount)
  {
    $startrow = 0;  //表示開始レコード
    $allpage = 1;  //全ページ
    $allcount = 0;  //全件数
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
  //  全データ件数取得
  /*****************************/
  function GetAllDataCount($dt)
  {
    foreach ($dt as $dr)
    {
      return $dr['count'];
   	}
  }

  /*****************************/
  //  カラー取得
  /*****************************/
  function GetColor(&$item)
  {
    print '(GetColor)<br>';
    if($this->CheckLogin())
    {
      $session = $this->GetSession();
      $item['board_backcolor'] = $session['board_backcolor'];
      $item['comment_backcolor'] = $session['comment_backcolor'];
      $item['subcomment_backcolor'] = $session['subcomment_backcolor'];
      $item['body_backcolor'] = $session['body_backcolor'];
      $item['commentboard_backcolor'] = $session['commentboard_backcolor'];
      $item['titel_backcolor'] = $session['titel_backcolor'];
    }
    else
    {
      $item['board_backcolor'] = '#40e0d0';
      $item['comment_backcolor'] = '#b0e0e6';
      $item['subcomment_backcolor'] = '#add8e6';
      $item['body_backcolor'] = '#ffffff';
      $item['commentboard_backcolor'] = '#48d1cc';
      $item['titel_backcolor'] = '#1e90ff';
    }
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
        case 'board_backcolor';
          $_SESSION['board_backcolor'] = $value;
          break;
        case 'comment_backcolor';
          $_SESSION['comment_backcolor'] = $value;
          break;
        case 'body_backcolor';
          $_SESSION['body_backcolor'] = $value;
          break;
        case 'subcomment_backcolor';
          $_SESSION['subcomment_backcolor'] = $value;
          break;
        case 'commentboard_backcolor';
          $_SESSION['commentboard_backcolor'] = $value;
          break;
        case 'titel_backcolor';
          $_SESSION['titel_backcolor'] = $value;
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
    $data =  array(
              'admin_id'=>$_SESSION['admin_id'], 
              'admin_pass_word'=>$_SESSION['admin_pass_word'], 
              'limitpageline'=>$_SESSION['limitpageline'], 
              'board_backcolor'=> $_SESSION['board_backcolor'], 
              'comment_backcolor'=>$_SESSION['comment_backcolor'],
              'body_backcolor'=>$_SESSION['body_backcolor'],
              'subcomment_backcolor'=>$_SESSION['subcomment_backcolor'],
              'commentboard_backcolor'=>$_SESSION['commentboard_backcolor'],
              'titel_backcolor'=>$_SESSION['titel_backcolor']
              );
    return $data;
  }

  /*****************************/
  //  セッション管理者ID取得
  /*****************************/
  function GetSessionAdminID()
  {
    return $_SESSION['admin_id']; 
  }
}
?>
