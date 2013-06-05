<?php
  /******************************/
  /*  データ一時保存  */
  /******************************/
class DataSaveClass
{
  private static $singleton = null;

  private $save_handlename = null;
  private $save_title = null;
  private $save_contents = null;
  private $save_pass_word = null;
  
  public static function GetInstance()
  {
    if (is_null(self::$singleton)) 
    {
      self::$singleton = new self;
    }
echo "インスタンスは既に存在します\n";
    return self::$singleton;
  }

  // コンストラクタ
  private function __construct()
  {
    echo "インスタンスを生成しました<br>";
  }

  public function SetData($handlename, $title, $contents, $pass_word)
  {
    $this->save_handlename = $handlename;
    $this->save_title = $title;
    $this->save_contents = $contents;
    $this->save_pass_word = $pass_word;
  }

  public function GetHandlename()
  {
    return $this->save_handlename;
  }
  public function GetTitle()
  {
    return $this->save_title;
  }
  public function GetContents()
  {
    return $this->save_contents;
  }
  public function GetPass_word()
  {
    return $this->save_pass_word;
  }
}
?>