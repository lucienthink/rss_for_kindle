<?php


$mysql = new SaeMysql();

function trans($url){
	$doc = new DOMDocument();
	if(@$doc->load($url)){
		$title = $doc->getElementsByTagName('title');
		$elements = $doc->getElementsByTagName('description');
		$titles = '';
		$content = '<h1>'.$title->item(0)->nodeValue.'</h1>';
		for ($i=0; $elements->item($i) ; $i++){
			$titles .= ($i == 0)?'<div><h3><a href="#'.$title->item($i)->nodeValue.'">'.$title->item($i)->nodeValue.'</a><h3></div>':'<div><a href="#'.$title->item($i)->nodeValue.'">'.$title->item($i)->nodeValue.'</a></div>';
			$content .= '<div id="'.$title->item($i)->nodeValue.'"><h3>'.$title->item($i)->nodeValue.'</h3></div>'.$elements->item($i)->nodeValue;
		}
		$result[0] = $title->item(0)->nodeValue;
		$result[1] = $titles;
		$result[2] = $content;
		return $result;	
	}	
}
function send($dd,$user,$mysql){
	$mail = new SaeMail();
	//$mail->setOpt( array( 
    //    'from' => 'xy604925299@sina.cn', 
    //    'to' => $user['mail'], 
    //    'smtp_host' => 'smtp.sina.com', 
    //    'smtp_port' => '25', 
    //    'smtp_username' => 'xy604925299@sina.cn', 
    //    'smtp_password' => '19910822', 
    //    'subject' => 'rss', 
    //    'content' => ''
//
//
    //    ) );
    
    $s = 8;
    $num = count($dd);
    while (($s-8)< $num) {
    	$name = ($num<=8)?'rss--'.date('Y-m-d').'.html':'rss--'.date('Y-m-d').'--'.ceil($s/8).'.html';
	    $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>'.$name.'</title></heaad><body>';
		for ($i=$s-8; $i < $num; $i++) {
			echo "<script>$('.bar').attr('style','width:".ceil((($i+1)/count($dd))*100)."%;')</script>";
			$result[$i] = trans($dd[$i]['url']);
			$html .= $result[$i][1].'<br>';
			if($i == $s-1) break;
		}
		for ($i=$s-8; $i < $num; $i++) { 
			$html .= $result[$i][2];		
			if($i == $s-1) break;
		}
		$html .= '</body><html>';
		$mail->setAttach( array( $name => $html ));
		$s += 8;
	}	
	$ret = $mail->quickSend( $user['mail'] , 'rss'.date('Y-m-d H:i:s') , '' , SMTP_NAME , SMTP_PASSWD);
	//$ret = $mail->send();
    if ($ret === false){
    	//var_dump($mail->errno(), $mail->errmsg());
	    $mail->clean();
	    $ret = $mail->quickSend( $user['mail'] , 'rss'.date('Y-m-d H:i:s') , '' , SMTP_NAME , SMTP_PASSWD , 'smtp.sina.com' ,25);
    }
    
    $mail->errno();
}

function feed($user,$type,$mysql){
	$sql = "SELECT * from ".PRE."feed where userid=";
	if($type == 1)
	{
		include_once("rss-template/feeding.html");
		$sql .= $user['id'];
		$data = $mysql->getData( $sql );
		if($data){
			send($data,$user,$mysql);
		}
	}
	elseif($type == 0){
		for ($i=0; $i < count($user); $i++) { 
			$msql = $sql.$user[$i]['id'];
			$data = $mysql->getData($msql);
			if($data){
				send($data,$user[$i],$mysql);
			}
		}
	}
	else{
		die("Type is wrong!");
	}
}

if(!$_SESSION['id'])
{
	include_once("../config.php");
	$sql = "SELECT * from ".PRE."users where feedtime=".date("H");
	$user = $mysql->getData($sql);
	feed($user,0,$mysql);
}
else{
	$user['id'] = $_SESSION['id'];
	$user['mail'] = $_SESSION['mail'];
	feed($user,1,$mysql);
	echo "<script language='javascript' type='text/javascript'>";  
	echo "window.location.href='?m=feed'";  
	echo "</script>";
}
?>