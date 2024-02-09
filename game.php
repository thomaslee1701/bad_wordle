<?php
class Game {
    private $player_guesses;
    private $num_correct_guesses;
    private $table_name;
    private $pdo;

    function __construct() {
        $this->player_guesses = array();
        $this->num_correct_guesses = 0;
        $this->table_name = "wordle_words_copy";
        $this->pdo = new PDO("mysql:host=localhost;dbname=php_practice", "root", "");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try { // try to create a copy of the table

            // Check if the copy table exists already
            $stmt = $this->pdo->prepare("DROP TABLE IF EXISTS $this->table_name");
            $stmt->execute();

            $stmt = $this->pdo->prepare("CREATE TABLE $this->table_name LIKE wordle_words");
            $stmt->execute();
            $stmt = $this->pdo->prepare("INSERT INTO $this->table_name SELECT * FROM wordle_words");
            $stmt->execute();
            
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }

    private function check_win() {
        return $this->num_correct_guesses == 5;
    }

    function process_guess($guess) {
        if (($this->check_win()) or (strlen($guess) != 5)) {
            return;
        }

        try { 
            for ($i = 1; $i < 5; $i++) {
                $letter = $guess[$i];
    
                $q = "SELECT COUNT(*) FROM $this->table_name WHERE word LIKE '%$letter%'";
                $stmt = $this->pdo->prepare($q);
                $stmt->execute();
    
                $q = "DELETE FROM $this->table_name WHERE word LIKE '%$letter%'";
                $stmt = $this->pdo->prepare($q);
                $stmt->execute();
                break; // die for now
            }
            
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }
}

$game = new Game();
