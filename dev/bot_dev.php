<?php

use afagorn\DialogueTelegramBot\examples\commands\StartCommand;
use afagorn\DialogueTelegramBot\DialogueBot;
use afagorn\DialogueTelegramBot\TelegramAPI;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor'  . DIRECTORY_SEPARATOR . 'autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/*-----------*/

$dialogueBot = new DialogueBot(new TelegramAPI(
    getenv("TELEGRAM_BOT_TOKEN"),
    new \afagorn\DialogueTelegramBot\DTO\ProxyDTO(
        getenv("PROXY_SOCKS_IP"),
        getenv("PROXY_SOCKS_PORT"),
        getenv("PROXY_SOCKS_LOGIN"),
        getenv("PROXY_SOCKS_PASSWORD")
    )
));

$dialogueBot->getUpdatesHandler()->configGetUpdatesMethod(10);

$dialogueBot->addCommands([
    new StartCommand()
]);

$dialogueBot->startUpdatesLoop();