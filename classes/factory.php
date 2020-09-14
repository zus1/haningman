<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/handler.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/htmlparser.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/controller.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/filehandler.php";
class Factory
{
    const TYPE_HANDLER = "handler";
    const TYPE_HTML_PARSER = 'htmlparser';
    const TYPE_CONTROLLER = "controller";
    const TYPE_FILE_HANDLER = "filehandler";
    const TYPE_METHOD_MAPPING = array(
        self::TYPE_HANDLER => 'getHandlerObject',
        self::TYPE_HTML_PARSER => 'getHtmlParserObject',
        self::TYPE_CONTROLLER => 'getControllerObject',
        self::TYPE_FILE_HANDLER => 'getFileHandlerObject',
    );


    /**
     * @param string $objType
     * @return HtmlParser|Handler|Controller|FileHandler
     * @throws Exception
     */
    public static function getObject(string $objType) {
        if(!array_key_exists($objType, self::TYPE_METHOD_MAPPING)) {
            throw new Exception("Unknown object", 404);
        }

        return call_user_func(array(new self(), self::TYPE_METHOD_MAPPING[$objType]));
    }

    private function getHandlerObject() {
        return new Handler();
    }

    private function getHtmlParserObject() {
        return new HtmlParser();
    }

    private function getControllerObject() {
        return new Controller($this->getFileHandlerObject());
    }

    private function getFileHandlerObject() {
        return new FileHandler();
    }
}