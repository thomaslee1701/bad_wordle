<?php
$table_name = "wordle_words_copy";
$pdo = new PDO("mysql:host=localhost;dbname=php_practice", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try { // try to create a copy of the table

    // Check if the copy table exists already
    $stmt = $pdo->prepare("DROP TABLE IF EXISTS $table_name");
    $stmt->execute();
    $stmt = $pdo->prepare("CREATE TABLE $table_name LIKE wordle_words");
    $stmt->execute();
    $stmt = $pdo->prepare("INSERT INTO $table_name SELECT * FROM wordle_words");
    $stmt->execute();

    // Create guessed word table
    $stmt = $pdo->prepare("DROP TABLE IF EXISTS player_guesses");
    $stmt->execute();
    $stmt = $pdo->prepare("CREATE TABLE player_guesses (guess VARCHAR(5) NOT NULL)");
    $stmt->execute();

    
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
    die();
}

header('Location: ../index.php');
