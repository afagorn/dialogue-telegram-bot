<?php
namespace DialogueTelegramBotSDK;

use DialogueTelegramBotSDK\Commands\Command;

class DialogueBot
{
    /**
     * @var UpdatesHandler
     */
    private $updatesHandler;

    /**
     * @var TelegramAPI
     */
    private $telegramAPI;

    /**
     * DialogueBot constructor.
     * @param TelegramAPI $telegramAPI
     */
    public function __construct(TelegramAPI $telegramAPI)
    {
        $this->telegramAPI = $telegramAPI;
        $this->updatesHandler = new UpdatesHandler($telegramAPI);
    }

    /**
     * @return TelegramAPI
     */
    public function getTelegramAPI()
    {
        return $this->telegramAPI;
    }

    /**
     * @return UpdatesHandler
     */
    public function getUpdatesHandler()
    {
        return $this->updatesHandler;
    }

    /**
     * @param Command[] $command
     */
    public function addCommands(array $command)
    {
        $this->updatesHandler->getCommandHandler()->addCommands($command);
    }

    /**
     * Запуск loop по обработке входящих Update. Цикл начинается с последнего Update
     */
    public function startUpdatesLoop()
    {
        $this->updatesHandler->startLoop();
    }
}
