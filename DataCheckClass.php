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
        case 'comment':
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          break;
        case 'pass_word':
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          $ret.= $this->LengthCheck($value, $this->itemnamearray[$key], 4);
          $ret.= $this->AlphaNumeralCheck($value, $this->itemnamearray[$key]);
          break;
        case 'admin_id':
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          break;
        case 'admin_pass_word':
          $ret.= $this->EmptyCheck($value, $this->itemnamearray[$key]);
          $ret.= $this->LengthCheck($value, $this->itemnamearray[$key], 4);
          $ret.= $this->AlphaNumeralCheck($value, $this->itemnamearray[$key]);
          break;
        case 'limitpageline':
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
    $dt = $db->GetComment($comment_id, '');
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
  }
  /*****************************/
  //  ログインチェック
  /*****************************/
  function CheckLogin()
  {
    if(isset($_COOKIE['admin_id']) || (isset($_SESSION['admin_id']) && isset($_SESSION['admin_pass_word'])))
    {
      if(!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_pass_word']))
      {
        $db = new DBClass();
        $this->SetSession($this->SetDataToSessionItem($db->GetAdminInfo($this->GetSessionAdminID())));
      }
      return true;
    }
    else
    {
      return false;
    }
    
  }

  /*****************************/
  //  セッション変数設定
  /*****************************/
  function SetDataToSessionItem($dt)
  {
    $item = '';
    foreach ($dt as $dr)
    {
      $item = array(
        'admin_id'=>$dr['admin_id'],
        'admin_pass_word'=>$dr['admin_pass_word'],
        'board_backcolor'=>$dr['board_backcolor'],
        'comment_backcolor'=>$dr['comment_backcolor'],
        'limitpageline'=>$dr['limitpageline'],
        'body_backcolor'=>$dr['body_backcolor'],
        'subcomment_backcolor'=>$dr['subcomment_backcolor'],
        'commentboard_backcolor'=>$dr['commentboard_backcolor'],
        'titel_backcolor'=>$dr['titel_backcolor']
        );
    }
    return $item;
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
    return isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : $_COOKIE['admin_id']; 
  }
  
  /******************************/
  //  添付ファイルチェック
  /******************************/
  function InputImgDataCheck($item,&$tmp)
  {
    $ret='';
    $fileext= null;
    $uploadedfile = null;
    $tmp='';
    foreach ($item as $key => $value)
    {
      switch ($key)
      {
        case 'imagefile':
          $uploadedfile = $value;
          $imginfo = getimagesize($value);
          if($imginfo[2] != IMAGETYPE_JPEG && $imginfo[2] != IMAGETYPE_GIF && $imginfo[2] != IMAGETYPE_PNG )
          {
            $ret.= 'JPG,PNG,GIF以外のファイル形式は添付できません。';
          }
          break;
        case 'imagefile_name':
          //$fileinfo = pathinfo($value);
          $fileext = strtolower(pathinfo($value)['extension']);
          break;
        case 'imagefile_size':
          if(1000000<$value)
          {
            $ret.= 'ファイルのサイズが大きすぎます。1MB以下にしてください。';
          }
          break;
        case 'imagefile_err':
          break;
        default:
          break;
      }
    }
    
    if(0==strlen($ret))
    {//エラーが無いとき一時フォルダに名前を変えて保存
      $mictime = microtime();
      $imagefile = substr($mictime,11).substr($mictime, 2, 6).'.'.$fileext;
      $path = ViewClass::$patharray['tmp'];
      if(!move_uploaded_file($uploadedfile, "$path$imagefile"))
      {
        $ret = 'イメージファイルのアップロードに失敗しました。';
        @unlink($uploadedfile);
        @unlink("$path$imagefile");
      }
      $tmp = "$path$imagefile";
    }
    return $ret;
  }
  
  /******************************/
  //  添付縮小
  /******************************/
  function Zoomout($img, &$new_width, &$new_height)
  {
    list($width, $height, $type, $attr) = getimagesize($img);
    switch ($type)
    {//タイプ別
      case IMAGETYPE_JPEG:
        $image = ImageCreateFromJPEG($img); //JPEGファイルを読み込む
        break;
      case IMAGETYPE_GIF:
        $image = ImageCreateFromGIF($img); //GIF画像を読み込む
        break;
      case IMAGETYPE_PNG:
        $image = ImageCreateFromPNG($img); //PNG画像を読み込む場合
        break;
      default:
        break;
    }
    //回転する場合は、下記のようにする　引数は、画像、角度、回転後にカバーされない部分に利用される背景色
    //$image = imagerotate($image, 90, 0);

    //縮小サイズ決定
    $new_width = ViewClass::$sizearray['width'];
    $rate = $new_width / $width; //圧縮比を求める
    $new_height = $rate * $height;

    // 空の画像を作成する。
    $new_image = ImageCreateTrueColor($new_width, $new_height);
 
    //リサイズ サンプリングしなおす場合。(ImageCopyResizedよりこっちの方が綺麗みたい)
    ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);

    //ファイル保存(数値は品質)
    switch ($type)
    {//タイプ別
      case IMAGETYPE_JPEG:
        ImageJPEG($new_image, $img, 70);
        break;
      case IMAGETYPE_GIF:
        ImageGIF($new_image, $img, 70);
        break;
      case IMAGETYPE_PNG:
        ImagePNG($new_image, $img, 70);
        break;
      default:
        break;
    }

    //後処理 メモリ解放
    ImageDestroy($image);
    ImageDestroy($new_image);

  }
  
  /******************************/
  //  ファイル名取得
  /******************************/
  function GetFileName($tmpfile)
  {
    if(!strpos($tmpfile, ViewClass::$patharray['image']))
    {
      return str_replace(ViewClass::$patharray['tmp'], '', $tmpfile);
    }
    else
    {
      return str_replace(ViewClass::$patharray['image'], '', $tmpfile);
    }
  }
  
  /******************************/
  //  本登録画像パス取得
  /******************************/
  function GetReleaseImageFile($img)
  {
    return 0 < strlen($img) ? ViewClass::$patharray['image'].$img : "";
  }
  
  /******************************/
  //  イメージファイル移動
  /******************************/
  function ReleaseImageFile($name)
  {
    if(1 > strlen($name))
    {//添付なし
      return;
    }

    $oldpath = ViewClass::$patharray['tmp'];
    $newpath = ViewClass::$patharray['image'];

    if(!file_exists("$oldpath$name"))
    {//以前の添付を使用なにもしない
      return;
    }
    
    //新規添付ファイル追加
    rename ("$oldpath$name", "$newpath$name");
  }
    
  /******************************/
  //  画像ファイルサイズ取得
  /******************************/
  function GetImageSize($img, &$width, &$height)
  {
    list($width, $height, $type, $attr) = getimagesize($img);
  }
    
  /******************************/
  //  添付画像ファイル削除
  /******************************/
  function DeleteTmpImage($img)
  {
     unlink($img);
  }
    
  /******************************/
  //  本登録画像ファイルチェック
  /******************************/
  function CheckReleaseImage($img)
  {
    return false === strpos($img, ViewClass::$patharray['image']) ? false : true;
  }

  /******************************/
  //  本登録画像ファイル削除
  /******************************/
  function DeleteOldReleaseImage($comment_id, $img)
  {
    $db = new DBClass();
    $dt = $db->GetComment($comment_id, '');
    $old_imagefile = '';
    foreach($dt as $dr)
    {
      if($img != $dr['img'])
      {//今回の添付と以前の添付が違ったら
        $old_imagefile = $dr['img'];
      }
    }
    
    if(0 < strlen($old_imagefile))
    {
      $path = ViewClass::$patharray['image'];
      $old_imagefile = "$path$old_imagefile";
      $this->DeleteTmpImage($old_imagefile);
    }
  }

  /******************************/
  //  添付ファイル削除
  /******************************/
  function DeleteOldImage($delcheck, $old_imagefile, $imagefile)
  {
    //以前添付なし
    if(1 > strlen($old_imagefile))
    {
      return;
    }
    
    //今回添付なし
    if(1 > strlen($imagefile))
    {
      if("0" != $delcheck)
      {//削除チェックあり
        if(!$this->CheckReleaseImage($old_imagefile))
        {//一時添付なので削除する
          $this->DeleteTmpImage($old_imagefile);
        }
      }
      return;
    }

    //今回添付あり・以前添付は無条件に削除対象となる
    if(!$this->CheckReleaseImage($old_imagefile))
    {//一時添付なので削除する
      $this->DeleteTmpImage($old_imagefile);
    }
    return;
  }

  /******************************/
  //  本登録添付ファイル取得
  /******************************/
  function GetReleaseImage($comment_id, $board_id)
  {
    $db = new DBClass();
    $dt = $db->GetComment($comment_id, $board_id);
    $img[] = '';
    $cnt = 0;
    print '(GetReleaseImage)count(img)='.count($img).';<br>';
    foreach($dt as $dr)
    {
      if(0 < strlen($dr['img']))
      { 
        $img[$cnt] = $dr['img'];
        $cnt++;
        print '(GetReleaseImage)='.$dr['img'].';<br>';
      } 
    }
    print '(GetReleaseImage)count(img)='.count($img).';<br>';
    return $img;
  }

  /******************************/
  //  本登録添付ファイル取得
  /******************************/
  function DeleteReleaseImage($img)
  {
      print 'count(img)='.count($img).';<br>';
    $path = ViewClass::$patharray['image'];
    for ($i = 0; $i < count($img); $i++)
    {
      $imagefile = $img[$i];
      print 'imagefile='.$imagefile.';<br>';
      $this->DeleteTmpImage("$path$imagefile");
    }
  }

}//class end
?>
