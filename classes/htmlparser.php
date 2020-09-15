<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/handler.php";
class HtmlParser
{
    private $partsRoot;

    public function __construct() {
        $this->partsRoot = $_SERVER['DOCUMENT_ROOT'] . "/htmlParts";
    }

    public function includeHeader() {
        $headerPath = $this->partsRoot . "/header.php";
        $contents = file_get_contents($headerPath);

        echo $this->replaceHolders($contents);
    }

    public function includeFooter() {
        $footerPath = $this->partsRoot . "/footer.php";
        $contents = file_get_contents($footerPath);

        echo $this->replaceHolders($contents);
    }

    public function includeView(string $viewName) {
        $viewPath = $this->partsRoot . "/" . $viewName . ".php";
        $contents = file_get_contents($viewPath);

        echo $this->replaceHolders($contents);
    }

    private function replaceHolders(string $fileContents) {
        $holdersMapping = array(
            '{bootstrap_css}' => Handler::baseUrl() . "css/bootstrap.css",
            '{bootstrap_js}' => Handler::baseUrl() . "js/bootstrap.min.js",
            "{main_css}" => Handler::baseUrl() . "css/main.css",
            "{start_js}" => Handler::baseUrl() . "js/start.js",
            "{game_js}" => Handler::baseUrl() . "js/game.js",
            "{utilities_js}" => Handler::baseUrl() . "js/utilities.js"
        );
        array_walk($holdersMapping, function ($value, $key) use(&$fileContents) {
           if(strpos($fileContents, $key)) {
               $fileContents = str_replace($key, $value, $fileContents);
           }
        });

        return $fileContents;
    }
}
