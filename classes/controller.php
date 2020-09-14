<?php

class Controller
{
    private $fileHandler;
    private $maxAttempts = 7;

    public function __construct(FileHandler $fileHandler) {
        session_start();
        $this->fileHandler = $fileHandler;
    }

    public function initNewGame(string $language) {
        /*unset($_SESSION['game_file']);
        return;*/
        if(isset($_SESSION['game_file'])) {
            throw new Exception("Game already started", 403);
        }
        list($gameFilePath, $fileName) = $this->fileHandler->getNewGameFile();
        $gameWords = $this->fileHandler->getGameWords($language);
        $randKey = array_rand($gameWords);

        $randWord = $gameWords[$randKey];
        $length = strlen($randWord);
        $iniStates = array_fill(0, $length, "");
        $indexes = array();
        array_walk($iniStates, function($value, $key) use(&$indexes) {
            $indexes[] = $key;
        });
        $stateString = array_combine($indexes, $iniStates);
        $contents = sprintf("%s,%s,%s,%s", $randWord, $this->maxAttempts, 0, json_encode($stateString)); //word, attempts left, attempts spent, guessed letters array
        if(file_put_contents($gameFilePath, $contents) !== false) {
            $_SESSION['game_file'] = $fileName;
        } else {
            throw new Exception("Could not create game file", 500);
        };
    }
}