<?php 
    require_once "game.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="includes/reset_game.php" method="post">
        <button type="submit">Start New Game</button>
    </form>

    <form action="index.php" method="post">
        <input type="text" name="user_guess">
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
