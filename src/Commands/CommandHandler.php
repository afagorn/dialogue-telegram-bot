<?php

namespace DialogueTelegramBotSDK\Commands;

use DialogueTelegramBotSDK\TelegramAPI;
use unreal4u\TelegramAPI\Telegram\Types\Update;

class CommandHandler
{
    /**
     * Массив Команд, где ключи - это их имена
     * @var Command[]
     */
    private $commands = [];

    /**
     * @var TelegramAPI
     */
    private $telegramAPI;

    /**
     * CommandHandler constructor.
     * @param TelegramAPI $telegramAPI
     */
    public function __construct(TelegramAPI $telegramAPI)
    {
        $this->telegramAPI = $telegramAPI;
    }

    /**
     * @param Command[] $commands
     */
    public function addCommands(array $commands)
    {
        foreach ($commands as $command) {
            if(!$command instanceof Command)
                throw new \InvalidArgumentException('Attempt to add invalid command');

            if(array_key_exists($command->getName(), $this->commands))
                throw new \InvalidArgumentException('Double command');

            $this->commands[$command->getName()] = $command;
        }
    }

    /**
     * @param Update $update
     */
    public function handleUpdateCommand(Update $update)
    {
        $parse = $this->parseCommand($update->message->text);
        $commandName = $parse[1];
        $param = $parse[3];

        //var_dump($parse);

        if(array_key_exists($commandName, $this->commands))
            $this->commands[$commandName]->init($this->telegramAPI, $update, $param);
        else
            $this->telegramAPI->sendMessage('Такой команды я не знаю. Попробуйте другую команду');

        //var_dump($this->commands[$commandName]);
    }

    /**
     * @param string $message
     * @return array
     */
    private function parseCommand(string $message)
    {
        if (trim($message) === '')
            throw new \InvalidArgumentException('Message is empty, Cannot parse for command');

        preg_match('/^\/([^\s@]+)@?(\S+)?\s?(.*)$/', $message, $matches);

        return $matches;
    }
}
