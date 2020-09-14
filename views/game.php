<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/htmlparser.php";
$parser = new HtmlParser();
$parser->includeHeader();
$parser->includeView('game');
$parser->includeFooter();
