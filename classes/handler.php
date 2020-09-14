<?php

class Handler
{
    public static function baseUrl() {
        $https = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off")? "https" : "http";
        $server = $_SERVER["SERVER_NAME"];
        return sprintf("%s://%s/", $https, $server);
    }

    public static function root(string $directory="") {
        return $_SERVER["DOCUMENT_ROOT"] . "/" . $directory;
    }

    public static function handleInclude(string $filename, string $directory="") {
        $file = self::root($directory). "/" . $filename . ".php";
        include($file);
    }
}