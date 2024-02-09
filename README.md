# bad_wordle
By: Thomas Lee

I hope you have fun playing! It was definitely fun making it. 

## Setup
- XAMPP and PHP will be necessary. This is not supposed to be a large project, so I just used my computer as the server with XAMPP. 
- I used a wordle list from here https://gist.github.com/dracos/dd0668f281e685bad51479e5acaadb93. The DB name `php_practice` and table name `wordle_words` is hardcoded.
- Other than that, there should be no other setup steps. 

## How to play
- The game plays like the popular NYTimes game wordle. It is incredibly bare-bones, so it is not as pretty as wordle.
- Notably, there is no "secret word" like wordle has. Instead, the game starts with a database table full of viable words. With each word that is guessed, the game will update the table and keep words that do not match up with the guess. 
- For example, if the user guesses **stare**, the game will remove all words in the word bank that have the letters **s**, **t**, **a**, **r**, and **e**. If it is unable to find such a word, it prioritized words that have the letters but in the wrong places. 
- The result of this is that the user only wins if all the words are narrowed down. 
