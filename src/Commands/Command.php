<?php
namespace afagorn\DialogueTelegramBotSDK\Commands;

use afagorn\DialogueTelegramBotSDK\TelegramAPI;
use unreal4u\TelegramAPI\Telegram\Types\Update;

abstract class Command implements ICommand
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var TelegramAPI
     */
    protected $telegramAPI;

    /**
     * @var Update
     */
    protected $update;

    public function __construct()
    {
        $this->setName();
    }

    /**
     * @param TelegramAPI $telegramAPI
     * @param Update $update
     * @param string $param
     */
    public function init(TelegramAPI $telegramAPI, Update $update, $param = '')
    {
        $this->telegramAPI = $telegramAPI;
        $this->update = $update;
        $this->handle($param);
    }

    public function getName()
    {
        return $this->name;
    }
}
