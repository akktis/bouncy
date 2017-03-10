<?php
	include("Downloader.php");
	
	//use IWantThis;
	
	$block = new \IWantThis\BlockModel();
	$content = $block->getPage('http://youtube.com', 'toto');
	$block->processContent();
	file_put_contents("test.html", $content);
?>