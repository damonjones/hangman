#Hangman API
In this assignment we ask you to implement a minimal version of a hangman API using the following resources below:

##Resources

##Start a new game
POST: /games
At the start of the game a random word should be picked from this list.


##Guess a started game
PUT: /games/:id

Guessing a correct letter doesn’t decrement the amount of tries left
Only valid characters are a-z

##Response (JSON)
Every response should contain the following fields:
* word: representation of the word that is being guessed. Should contain dots for letters that have not been guessed yet (e.g. aw.so..)
* tries_left: the number of tries left to guess the word (starts at 11)
* status: current status of the game (busy|fail|success)
