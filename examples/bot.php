<?php
include "vendor/autoload.php";

use afagorn\DialogueTelegramBot\examples\commands\StartCommand;
use afagorn\DialogueTelegramBot\DialogueBot;
use afagorn\DialogueTelegramBot\TelegramAPI;

$dialogueBot = new DialogueBot(new TelegramAPI('TELEGRAM_BOT_TOKEN'));

$dialogueBot->getUpdatesHandler()->configGetUpdatesMethod(10);

$dialogueBot->addCommands([
    new StartCommand()
]);

$dialogueBot->startUpdatesLoop();