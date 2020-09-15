<?php
//include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/htmlparser.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/factory.php";
$parser = Factory::getObject(Factory::TYPE_HTML_PARSER);
$parser->includeHeader();
$parser->includeView("game");
$parser->includeFooter();
