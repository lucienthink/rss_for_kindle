<?php
/*
*FOR LOGIN
*BY lucien_think
*/
	class Account extends Safe{
		private $Mysql;
		private $username;
		private $password;


		function __construct(){
			$this->Mysql = new XYMysql();                    
		}

//GET JSON DATA		
		public function dataGet(){
			$this->username = $this->nameChecker($_REQUEST['username']);
			$this->password = $this->passwdChecker($_REQUEST['password']);
		}

//login the user
		public function login(){
			$this->dataGet();
			$user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
			$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
			$sql = "SELECT * from ".PRE."users where username = '".$this->username."' and password = '".$this->password."'";
			$data = $this->Mysql->getLine($sql);
			if($data) {
				$_SESSION['id'] = $data['id'];
				$_SESSION['username'] = $data['username'];
				$_SESSION['password'] = $data['password'];
				$_SESSION['mail'] = $data['mail'];
				$_SESSION['feedtime'] = $data['feedtime'];
				$_SESSION['lasttime'] = $data['lasttime'];
				$_SESSION['lastip'] = $data['lastip'];
				$_SESSION['thisip'] = $user_IP;

				$sql = "UPDATE ".PRE."users set lasttime='".date("Y-m-d H:i:s",time())."', lastip='".$user_IP."' where id=1";
				$this->Mysql->runSql($sql);
				if($this->Mysql->errno() != 0){
					die("Error:".$this->Mysql->errmsg());
				}
				echo "<script language='javascript' type='text/javascript'>";  
				echo "window.location.href='?m=feed'";  
				echo "</script>"; 
			}
			else{
				echo "<script language='javascript' type='text/javascript'>";  
				echo "alert('用户名或密码错误！请重新填写。');window.location.href='/'";  
				echo "</script>"; 
			}	
			
		}

//logout
		public function logout(){
			unset($_SESSION['id']);
			unset($_SESSION['username']);
			unset($_SESSION['password']);
			unset($_SESSION['lasttime']);
			unset($_SESSION['lastip']);
			session_destroy();

			echo "<script language='javascript' type='text/javascript'>";  
			echo "alert('成功登出！');window.location.href='/'";  
			echo "</script>"; 
			
		}

//signup 
		public function signup(){
			$this->dataGet();
			if(!$this->username||!$this->password){
				echo "<script language='javascript' type='text/javascript'>";  
				echo "alert('请填写email和密码！');window.location.href='/';";  
				echo "</script>"; 
			}
			$sql = "SELECT * from ".PRE."users where username = '".$this->username."' and password = '".$this->password."'";
			$data = $this->Mysql->getLine($sql);

			if(!$data) {
				$user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
				$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
				$lasttime = date("Y-m-d H:i:s",time());

				$sql = "INSERT INTO ".PRE."users ( username , password , lasttime , lastip , thisip ) values( '".$this->username."' , '".$this->password."' , '".$lasttime."' , '0' , '".$user_IP."' )";
				$this->Mysql->runSql($sql);
				if($this->Mysql->errno() != 0){
					die("Error:".$this->Mysql->errmsg());
				}
				else{
					$sql = "SELECT * from ".PRE."users where username = '".$this->username."' and password = '".$this->password."'";
					$data = $this->Mysql->getLine($sql);
					$_SESSION['id'] = $data['id'];
					$_SESSION['username'] = $this->username;
					$_SESSION['password'] = $this->password;
					$_SESSION['lasttime'] = $lasttime;
					$_SESSION['lastip'] = $data['lastip'];
					$_SESSION['thisip'] = $user_IP;

					echo "<script language='javascript' type='text/javascript'>";  
					echo "window.location.href='?m=feed'";  
					echo "</script>";  
				}
				
			}
			else{
				echo "<script language='javascript' type='text/javascript'>";  
				echo "alert('此邮箱已注册，请登录！');window.location.href='/';";  
				echo "</script>"; 
			}
		}

	}
?>