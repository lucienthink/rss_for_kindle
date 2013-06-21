<?php
	header('Content-type:text/html; charset=UTF-8');
	session_start();
	include_once("config.php");
	function __autoload($classname)
	{
		include ("rss-class/".strtolower($classname).".class.php");

	}
	switch ($_REQUEST['m']) {
		case 'signup':
			$m = new Account();
			$m->signup();
			break;
		case 'login':
			$m = new Account();
			$m->login();
			break;
		case 'logout':
			$m = new Account();
			$m->logout();
			break;
		case 'setting':
			$m = new Setting();
			include ("rss-template/setting.html");
			break;
		case 'setmake':
			$m = new Setting();
			$m->settingEdit();
			break;
		case 'feed':
			$m = new Feed();
			include ("rss-template/feed.html");
			break;
		case 'feedmake':
			$m = new Feed();
			$m->feedEdit();
			break;
		case 'feeddel':
			$m = new Feed();
			$m->feedDel();
			break;
		case 'hotrss':
			include ("rss-template/hotrss.html");
			break;
		case 'passwd':
			include ("rss-template/passwd.html");
			break;
		case 'repasswd':
			$m = new Setting;
			$m->password();
			break;
		case 'feedsend':
			include ("rss-data/feed.php");
			break;
		default:
			include_once "rss-template/index.html";
	}
?>