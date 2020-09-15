<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/handler.php";
class FileHandler
{
    const GAME_PREFIX = "game_file_";
    private $resourcesPath;
    private $resourcesUrl;

    public function __construct() {
        $this->resourcesPath = $_SERVER["DOCUMENT_ROOT"] . "/resources";
        $this->resourcesUrl = Handler::baseUrl() . "resources";
    }

    public function getNewGameFile() {
        $dt = new DateTime();

        $filename = self::GAME_PREFIX . $dt->format("Y-m-d") . "_" . microtime() . ".csv";
        $fullPath = $this->resourcesPath . "/game/" . $filename;
        if(file_exists($fullPath)) {
            return file_get_contents($fullPath);
        }
        if(touch($fullPath)) {
            chmod($fullPath, 0755);
            return array($fullPath, $filename);
        }

        throw new Exception("Could not init game", 500);
    }

    public function updateGameFile(string $filename, string $contents) {
        $fullPath = $this->resourcesPath . "/game/" . $filename;
        if(!file_put_contents($fullPath, $contents)) {
            throw new Exception("Could not process latter");
        }
    }

    public function deleteGameFile(string $filename) {
        $fullPath = $this->resourcesPath . "/game/" . $filename;
        unlink($fullPath);
    }

    public function getGameWords(string $language) {
        $fullPath = $this->resourcesPath . "/words/" . $language . ".csv";
        if(!file_exists($fullPath)) {
            throw new Exception("Language not supported", 404);
        }
        return explode(",", file_get_contents($fullPath));
    }

    public function getHangingManImageForFailedAttempts(int $failedAttempts) {
        $imgDirUrl = $this->resourcesUrl . "/images";

        return array(
            $imgDirUrl . "/0.jpg",
            $imgDirUrl . "/1.jpg",
            $imgDirUrl . "/2.jpg",
            $imgDirUrl . "/3.jpg",
            $imgDirUrl . "/4.jpg",
            $imgDirUrl . "/5.jpg",
            $imgDirUrl . "/6.jpg",
            $imgDirUrl . "/7.jpg",
        )[$failedAttempts];
    }

    public function getCurrentGameValues(string $filename) {
        $fullPath = $this->resourcesPath . "/game/" . $filename;
        if(!file_exists($fullPath)) {
            throw new Exception("No game file", 404);
        }

        return file_get_contents($fullPath);
    }
}