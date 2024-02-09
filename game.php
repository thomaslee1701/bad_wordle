<?php
class Game {
    private $player_guesses;
    private $table_name;
    private $pdo;
    private $color_mapper;

    function __construct() {
        $this->player_guesses = array();
        $this->num_correct_guesses = 0;
        $this->table_name = "wordle_words_copy";
        $this->pdo = new PDO("mysql:host=localhost;dbname=php_practice", "root", "");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->color_mapper = array(
            "grey" => "#808080",
            "green" => "#32CD32",
            "yellow" => "#E4D00A"
        );
    }

    private function total_table_size() {
        try { 
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM $this->table_name");
            $stmt->execute();
            $total_table_size = $stmt->fetchColumn();
            return $total_table_size;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }

    private function print_guessed_words() {
        try { 
            $str = '<div>';
            
            $stmt = $this->pdo->prepare("SELECT guess FROM player_guesses");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($results)) {
                return;
            }
            foreach (array_reverse($results) as $row) {
                $str = $str . '<div class="word-container">' . $row["guess"] . "</div>";
            }
            echo $str . "</div>";
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
        
    }

    function process_guess($guess) {
        $guess = strtolower($guess);
        if (strlen($guess) != 5) {
            echo "Invalid word";
            return;
        }
        $colors = array();
        try { 
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM $this->table_name");
            $stmt->execute();
            $total_table_size = $this->total_table_size();

            for ($i = 0; $i < 5; $i++) {
                $letter = $guess[$i];
                
                // First check if there are any words that don't contain that letter at all
                $q = "SELECT COUNT(*) FROM $this->table_name WHERE word NOT LIKE '%$letter%'";
                $stmt = $this->pdo->prepare($q);
                $stmt->execute();
                $count = $stmt->fetchColumn();
                if ($count > 0) {
                    $q = "DELETE FROM $this->table_name WHERE word LIKE '%$letter%'";
                    $stmt = $this->pdo->prepare($q);
                    $stmt->execute();
                    array_push($colors, "grey");
                    continue;
                }

                // Otherwise, check if there are any words that don't contain that letter in the ith position
                $q = "SELECT COUNT(*) FROM $this->table_name WHERE SUBSTRING(word, $i+1, 1) != '$letter'";
                $stmt = $this->pdo->prepare($q);
                $stmt->execute();
                $count = $stmt->fetchColumn();
                if ($count > 0) {
                    $q = "DELETE FROM $this->table_name WHERE SUBSTRING(word, $i+1, 1) = '$letter'";
                    $stmt = $this->pdo->prepare($q);
                    $stmt->execute();
                    array_push($colors, "yellow");
                    continue;
                }

                // Otherwise, give up the letter
                array_push($colors, "green");
            }

            $out_str = "";
            for ($i = 0; $i < 5; $i++) {
                $color = $this->color_mapper[$colors[$i]];
                $letter = $guess[$i];
                $out_str = $out_str . "<span style='color:$color;font-size:24px'>$letter</span>";
            }
            echo $out_str;
            $this->print_guessed_words();

            // Add the word to guessed words
            $stmt = $this->pdo->prepare("INSERT INTO player_guesses (guess) VALUES ('$guess')");
            $stmt->execute();
            
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }
}

$game = new Game();
