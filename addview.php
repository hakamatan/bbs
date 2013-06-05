<?php
class ViewClass
{
    public function DisplayInput($handlename, $title, $contents)
    {
      
    }
    // 新規確認画面
    public function DisplayInputNewCheck($handlename, $title, $contents, $pass_word)
    {  
      return '<form action="insert.php" method="post">
  				<table class="addview">
  					<tr><td width="150">名前</td><td>'.$handlename.'</td></tr>
  					<tr><td width="150">タイトル</td><td>'.$title.'</td></tr>
  					<tr><td width="150" valign="top">メッセージ</td><td></td></tr>
  					<tr><td colspan="2">'.$contents.'</td></tr>
  					<tr><td width="150" valign="top">更新・削除キー</td><td>●●●●</td></tr>
  					<tr align="center"><td colspan="2" align="right"> <input type="submit" name="addsub" value="書込み">
  																							<INPUT type="button" value="やり直し" onClick="history.back()"></td></tr>
  				</table> 
          <input type="hidden" name="handlename" value="'.$handlename.'"> 
          <input type="hidden" name="title" value="'.$title.'"> 
          <input type="hidden" name="contents" value="'.$contents.'"> 
          <input type="hidden" name="pass_word" value="'.$pass_word.'"> 
  			</form>';
    }

    // 新規画面
    public $handlename='<input type="text" name="handlename" size="50">';
    public $title='<input type="text" name="title" size="50">';
    public $contents='<textarea name="contents" cols="50" rows="3" wrap="soft"></textarea>';
    public $pass_word='<input type="password" name="pass_word" size="10">';

    public function DisplayInputNew() 
    {
        //return $this->InputNew;
        return '<form action="insertcheck.php" method="post">
  				<table  class="addview">
  					<tr><td width="150">名前</td><td>'.$this->handlename.'</td></tr>
  					<tr><td width="150">タイトル</td><td>'.$this->title.'</td></tr>
  					<tr><td width="150" valign="top">メッセージ</td><td></td></tr>
  					<tr><td colspan="2">'.$this->contents.'</td></tr>
  					<tr><td width="150" valign="top">更新・削除キー</td><td>'.$this->pass_word.'&nbsp;4ケタの半角英数字</td></tr>
  					<tr align="center"><td colspan="2" align="right"> <input type="submit" name="addsub" value="入力確認">
  																							<input type="reset" name="cancel" value="リセット"></td></tr>
  				</table>
  			</form>';
    }

}
?> 
