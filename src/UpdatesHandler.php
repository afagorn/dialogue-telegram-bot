<?php
namespace DialogueTelegramBotSDK;

use DialogueTelegramBotSDK\Commands\CommandHandler;
use unreal4u\TelegramAPI\Abstracts\TraversableCustomType;
use unreal4u\TelegramAPI\Telegram\Methods\GetUpdates;
use unreal4u\TelegramAPI\Telegram\Types\Update;

class UpdatesHandler
{
    /**
     * @var GetUpdates
     */
    private $getUpdatesMethod;

    /**
     * @var TelegramAPI
     */
    private $telegramAPI;

    /**
     * @var CommandHandler
     */
    private $commandHandler;

    /**
     * UpdatesHandler constructor.
     * @param TelegramAPI $telegramAPI
     */
    public function __construct(TelegramAPI $telegramAPI)
    {
        $this->telegramAPI = $telegramAPI;
        $this->commandHandler = new CommandHandler($telegramAPI);
        $this->getUpdatesMethod = new GetUpdates();
    }

    /**
     * @param int $timeout
     * @param int $offset
     * @param int $limit
     * @param array $allowedUpdates
     */
    public function configGetUpdatesMethod(int $timeout = 0, int $offset = 0, $limit = 100, $allowedUpdates = [])
    {
        $this->getUpdatesMethod->timeout = $timeout;
        $this->getUpdatesMethod->offset = $offset;
        $this->getUpdatesMethod->limit = $limit;
        $this->getUpdatesMethod->allowed_updates = $allowedUpdates;
    }

    public function getCommandHandler()
    {
        return $this->commandHandler;
    }

    /**
     * В цикле получаем обновления и обрабатываем каждое
     */
    public function startLoop()
    {
        $this->telegramAPI->sendMethod(
            $this->getUpdatesMethod,
            function (TraversableCustomType $updatesArray) {

                //var_dump($updatesArray);

                if(!empty($updatesArray->data)) {
                    foreach ($updatesArray as $update)
                        $this->updateHandle($update);

                    $this->increaseUpdatesOffset($update->update_id);
                }

                $this->startLoop();
            }
        );
    }

    /**
     * Если это команда, то пропарсить и запустить команду, иначе выводим заглушку
     * @param Update $update
     */
    private function updateHandle(Update $update)
    {
        var_dump($update->message->text);
        var_dump($update->message->chat->id);

        $this->telegramAPI->setCurrentChatId($update->message->chat->id);

        if(isset($update->message->entities)) {
            foreach ($update->message->entities as $entity) {
                if($entity->type == 'bot_command') {
                    $this->commandHandler->handleUpdateCommand($update);
                    return;
                }
            }
        }

        $this->telegramAPI->sendMessage('Пожалуйста, отправьте мне команду');

        return;
    }

    /**
     * @param int $currentUpdateId
     */
    private function increaseUpdatesOffset(int $currentUpdateId)
    {
        $this->getUpdatesMethod->offset = $currentUpdateId + 1;
    }
}
