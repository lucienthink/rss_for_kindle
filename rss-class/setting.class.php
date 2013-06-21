<?php
/*FOR SHOW INFORMATION
*
*
*BY LUCIEN
*/
class Setting extends Safe{
	private $mysql;
	private $mail;
	private $feedtime;
	private $oldpasswd;
	private $newpasswd;


	function __construct(){
		$this->mysql = new XYMysql();
		$this->sessionCheck();
	}

	public function dataGet(){
		$this->mail = $_POST['emailpre']?$this->nameChecker($_POST['emailpre']."@".$_POST['emailnext']):null;
		$this->feedtime =$_POST['feedtime']?$this->idChecker($_POST['feedtime']):null;
		$this->oldpasswd = $_POST['oldpasswd']?$this->passwdChecker($_POST['oldpasswd']):null;
		$this->newpasswd = $_POST['newpasswd']?$this->passwdChecker($_POST['newpasswd']):null;
		$this->repasswd = $_POST['repasswd']?$this->passwdChecker($_POST['repasswd']):null;
	}

	public function settingShow(){	
		$result = array();
		$result['feedtime'] = $_SESSION['feedtime'];
		$result['mail'] = explode("@", $_SESSION['mail']);
		
		if(!$result['feedtime']&&!$result['mail']){
			return null;
		}
		else{
			return $result;
		}
	}

	public function settingEdit(){
		$this->dataGet();
		$sql = "UPDATE ".PRE."users set mail='".$this->mail."', feedtime=".$this->feedtime." where id=".$_SESSION['id'];
		$this->mysql->runSql($sql);
		if($this->mysql->errno() != 0){
			die("Error:".$this->mysql->errmsg());
		}
		else{
			$_SESSION['mail'] = $this->mail;
			$_SESSION['feedtime'] = $this->feedtime;
			echo "<script language='javascript' type='text/javascript'>";  
			echo "window.location.href='?m=setting'";  
			echo "</script>"; 
		}
	}

	public function password(){
		$this->dataGet();
		if($this->newpasswd == $this->repasswd){
			if($_SESSION['password'] == $this->oldpasswd){
				$sql = "UPDATE ".PRE."users set password='".$this->newpasswd."' where id=".$_SESSION['id'];
				$this->mysql->runSql($sql);
				if($this->mysql->errno() != 0){
					echo "<script language='javascript' type='text/javascript'>";  
					echo "alert('系统错误，密码修改失败！');window.location.href='?m=passwd';";  
					echo "</script>"; 
				}
				else{
					$_SESSION['password'] = $this->newpasswd;
					echo "<script language='javascript' type='text/javascript'>";  
					echo "alert('密码修改成功！');window.location.href='?m=passwd';";  
					echo "</script>"; 
				}
			}
			else{
				echo "<script language='javascript' type='text/javascript'>";  
				echo "alert('原密码错误！');window.location.href='?m=passwd';";  
				echo "</script>"; 
			}
		}
		else{
			echo "<script language='javascript' type='text/javascript'>";  
			echo "alert('重复密码与新密码不一致！');window.location.href='?m=passwd';";  
			echo "</script>"; 
		}
		
	}

}



?>