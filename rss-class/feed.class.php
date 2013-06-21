<?php
/*FOR SHOW INFORMATION
*
*
*BY LUCIEN
*/
class Feed extends Safe{
	private $mysql;
	private $url;
	private $id;

	function __construct(){
		$this->mysql = new XYMysql();
		$this->sessionCheck();
	}
	public function dataGet(){;
		$this->url = $_REQUEST['url']?$this->textChecker($_REQUEST['url']):null;
		$this->url = preg_match('/http:\/\//', $this->url)?$this->url:'http://'.$this->url;
		$this->id = $_REQUEST['id']?$this->idChecker($_REQUEST['id']):null;
	}
	public function feedShow(){
		$sql = "SELECT * from ".PRE."feed where userid=".$_SESSION['id'];
		$data = $this->mysql->getData($sql);
		if($data){
			return $data;
		}
	}

	public function feedEdit(){
		$this->dataGet(); 
		//$lines_string = file_get_contents($this->url);
		//preg_match('/<title>(.*)<\/title>/', $lines_string, $head);
		$doc = new DOMDocument();
		if (@$doc->load( $this->url )) {
			$title = $doc->getElementsByTagName( "title" )->item(0)->nodeValue;
			$title = $title?$title:preg_replace('/http:\/\/([^\/]*)\/(.*)/', '$1' , $this->url );
			$sql = "INSERT INTO ".PRE."feed ( title , url , userid ) values( '".$title."' , '".$this->url."' , ".$_SESSION['id'].")";
			$this->mysql->runSql($sql);
			if($this->mysql->errno() != 0){
				die("Error:".$this->mysql->errmsg());
			}
			else{
				echo "<script language='javascript' type='text/javascript'>";  
				echo "window.location.href='?m=feed'";  
				echo "</script>";  
			}
		}else{
			$f = 1;
			include("rss-template/feed.html");
		}
	}

	public function feedDel(){
		$this->dataGet(); 
		$sql = "DELETE from ".PRE."feed where id=".$this->id;
		$this->mysql->runSql($sql);
		if($this->mysql->errno() != 0){
			die("Error:".$this->mysql->errmsg());
		}
		else{
			echo "<script language='javascript' type='text/javascript'>";  
			echo "window.location.href='?m=feed'";  
			echo "</script>";  
		}
	}


}



?>