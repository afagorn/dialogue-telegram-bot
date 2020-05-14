<?php
namespace afagorn\DialogueTelegramBot\examples\commands;

use afagorn\DialogueTelegramBotSDK\Commands\Command;

class StartCommand extends Command
{
    function setName()
    {
        $this->name = 'start';
    }

    function handle(string $param = '')
    {
        $this->telegramAPI->sendMessage(
            "Привет! Вы запустили команду '$this->name' \n" .
            (!empty($param) ? "Ваш текущий параметр $param" : "Вы не указали параметра для команды") . "\n\n" .
            "Ваш любимый бот"
        );
    }
}