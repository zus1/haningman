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
        $contents = sprintf("%s;%s;%s;%s", $randWord, $this->maxAttempts, 0, json_encode($stateString, JSON_FORCE_OBJECT)); //word, attempts left, attempts spent, guessed letters array
        if(file_put_contents($gameFilePath, $contents) !== false) {
            $_SESSION['game_file'] = $fileName;
        } else {
            throw new Exception("Could not create game file", 500);
        };
    }

    public function attemptLatter(string $latter) {
        $currentGameValues = $this-> getCurrentGameValues();
        $wordLetters = $currentGameValues["word_letters"];
        $hitsIndexes = array();
        $index = 0;
        while(($index = strpos($currentGameValues["word"], $latter, $index)) !== false) {
            $hitsIndexes[] = $index;
            $index++;
        }

        if(count($hitsIndexes) === 0) {
            $currentGameValues['attempt_left'] = intval($currentGameValues['attempt_left']) - 1;
            $currentGameValues['guess_count'] = intval($currentGameValues['guess_count']) + 1;
        } else {
            array_walk($hitsIndexes, function ($value) use (&$wordLetters, $latter) {
                $wordLetters[$value] = $latter;
            }) ;
            $currentGameValues["word_letters"] = $wordLetters;
            $currentGameValues['hit'] = 1;
        }
        $currentGameValues["image"] = $this->fileHandler->getHangingManImageForFailedAttempts(intval($currentGameValues['guess_count']));
        $currentGameValues['loss'] = (int)$this->isGameLost($currentGameValues);
        $currentGameValues["win"] = (int)$this->isGameWon($currentGameValues);

        $this->handleUpdateGameFile($currentGameValues);

        return $currentGameValues;
    }

    public function attemptSolution(string $solution) {
        $currentGameValues = $this-> getCurrentGameValues();
        $win = $solution === $currentGameValues['word'];
        $currentGameValues['word_letters'] = str_split($currentGameValues['word']);
        if($win === true) {
            $currentGameValues['win'] = intval($win);
            $currentGameValues['hit'] = 1;
        } else {
            $currentGameValues['attempt_left'] = 0;
            $currentGameValues['guess_count'] = $this->maxAttempts;
            $currentGameValues['image']  = $this->fileHandler->getHangingManImageForFailedAttempts(intval($this->maxAttempts));
            $currentGameValues['loss'] = (int)$this->isGameLost($currentGameValues);
        }

        $this->finishGame();

        return $currentGameValues;
    }

    public function concedeDefeat() {
        $currentGameValues = $this->getCurrentGameValues();
        $currentGameValues['attempt_left'] = 0;
        $currentGameValues['guess_count'] = $this->maxAttempts;
        $currentGameValues['loss'] = (int)$this->isGameLost($currentGameValues);
        $currentGameValues['image'] = $this->fileHandler->getHangingManImageForFailedAttempts(intval($this->maxAttempts));

        $this->finishGame();

        return $currentGameValues;
    }

    private function handleUpdateGameFile(array $gameValues) {
        if($gameValues['win'] == 1 || $gameValues['loss'] == 1) {
            $this->finishGame();
            return;
        }
        $contents = sprintf("%s;%s;%s;%s", $gameValues['word'], $gameValues['attempt_left'], $gameValues['guess_count'], json_encode($gameValues['word_letters'], JSON_FORCE_OBJECT));
        $this->fileHandler->updateGameFile($_SESSION['game_file'], $contents);
    }

    private function finishGame() {
        $this->fileHandler->deleteGameFile($_SESSION['game_file']);
        unset($_SESSION['game_file']);
    }

    public function getCurrentGameValues() {
        if(!isset($_SESSION['game_file'])) {
            throw new Exception("Could not find a game", 404);
        }
        $gameContents = $this->fileHandler->getCurrentGameValues($_SESSION["game_file"]);
        list($word, $attemptsLeft, $guessCount, $guessedLettersJson) = explode(";", $gameContents);

        $return = array(
            'word' => $word,
            'attempt_left' => $attemptsLeft,
            'guess_count' => $guessCount,
            'word_letters' => json_decode($guessedLettersJson, true),
            'image' => $this->fileHandler->getHangingManImageForFailedAttempts(intval($guessCount)),
            'hit' => 0, //do we have any guessed letters. overriden later on, used for notifications
        );
        $return['win'] = (int)$this->isGameWon($return);
        $return['loss'] = (int)$this->isGameLost($return);

        return $return;
    }

    private function isGameWon(array $gameValues) {
        $allGuessedLetters = array_values($gameValues['word_letters']);
        if(!in_array("", $allGuessedLetters)) {
            return true;
        }

        return false;
    }

    private function isGameLost(array $gameValues) {
        if($gameValues['attempt_left'] == 0) {
            return true;
        }

        return false;
    }
}