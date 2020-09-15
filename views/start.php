<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/factory.php";
$parser = Factory::getObject(Factory::TYPE_HTML_PARSER);
$parser->includeHeader();
$parser->includeView('start');
$parser->includeFooter();

