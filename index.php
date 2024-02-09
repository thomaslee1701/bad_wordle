<?php 
    require_once "game.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bad Wordle</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Water-dle</h1>
    <h2>By: Thomas Lee</h2>
    <p>A game made entirely in PHP that has little to do with water.</p>
    <form action="includes/reset_game.php" method="post">
        <button class="submit-button" type="submit">Start New Game</button>
    </form>

    <form action="index.php" method="post">
        <input class="textbox" type="text" name="user_guess" maxlength="5" placeholder="Type here">
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $guess = $_POST["user_guess"];
            $game->process_guess($guess);
            // Do something with the data
        }
    ?>

</body>
</html>
