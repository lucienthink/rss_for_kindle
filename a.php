<?php

$doc = new DOMDocument();
echo $dd = file_get_contents('http://book.douban.com/feed/review/book');
$doc->loadXML($dd);
?>