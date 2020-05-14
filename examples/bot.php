<?php
include "vendor/autoload.php";

use DialogueTelegramBotSDK\DialogueBot;
use DialogueTelegramBotSDK\TelegramAPI;

$dialogueBot = new DialogueBot(new TelegramAPI('TELEGRAM_BOT_TOKEN'));

$dialogueBot->getUpdatesHandler()->configGetUpdatesMethod(10);

$dialogueBot->addCommands([
    new StartCommand()
]);

$dialogueBot->startUpdatesLoop();