<?php
namespace afagorn\DialogueTelegramBotSDK;

use Clue\React\Socks\Client;
use afagorn\DialogueTelegramBotSDK\DTO\ProxyDTO;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use unreal4u\TelegramAPI\Abstracts\TelegramMethods;
use unreal4u\TelegramAPI\HttpClientRequestHandler;
use unreal4u\TelegramAPI\Telegram\Methods\SendChatAction;
use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\TgLog;

class TelegramAPI
{
    /**
     * @var TgLog
     */
    public $tgLog;

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var string
     */
    private $botToken;

    /**
     * @var int
     */
    private $currentChatId;

    /**
     * TelegramAPI constructor.
     * @param string $botToken
     * @param ProxyDTO|null $proxyDTO
     */
    public function __construct(string $botToken, ProxyDTO $proxyDTO = null)
    {
        $this->init($botToken, $proxyDTO);
        $this->botToken = $botToken;
    }

    public function setCurrentChatId(int $id)
    {
        $this->currentChatId = $id;
    }

    /**
     * @param TelegramMethods $method
     * @return \React\Promise\PromiseInterface
     */
    public function performApiRequest(TelegramMethods $method)
    {
        return $this->tgLog->performApiRequest($method);
    }

    /**
     * Отправка любого сообщения по currentChatId
     * @param string $text
     */
    public function sendMessage(string $text)
    {
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = $this->currentChatId;
        $sendMessage->text = $text;
        $this->performApiRequest($sendMessage);
    }

    /**
     * @param string $actionType
     */
    public function sendChatAction(string $actionType)
    {
        $chatActionMethod = new SendChatAction();
        $chatActionMethod->chat_id = $this->currentChatId;
        $chatActionMethod->action = $actionType;

        $this->performApiRequest($chatActionMethod);
    }

    /**
     * Отправка любого метода и необязательный callback после отправки
     * @param TelegramMethods $method
     * @param callable $callback
     */
    public function sendMethod(TelegramMethods $method, callable $callback = null)
    {
        $promise = $this->tgLog->performApiRequest($method);

        $promise->then(
            $callback,
            function (\Exception $exception) {
                echo 'Exception ' . get_class($exception) . ' caught, message: ' . $exception->getMessage();
            }
        );

        $this->loop->run();
    }

    /**
     * Подключем прокси если нужно, React и стороннюю библиотеку для работы с API телеграмма
     * @param string $botToken
     * @param ProxyDTO|null $proxyDTO
     */
    public function init(string $botToken, ProxyDTO $proxyDTO = null)
    {
        $loop = Factory::create();

        $options = [];
        if(!is_null($proxyDTO)) {
            $proxy = new Client($proxyDTO->getSocksUrl(), new \React\Socket\Connector($loop));
            $options = array_merge($options, [
                'tcp' => $proxy,
                'timeout' => 3.0,
                'dns' => false
            ]);
        }
        $handler = new HttpClientRequestHandler($loop, $options);
        $tgLog = new TgLog($botToken, $handler);

        $this->loop = $loop;
        $this->tgLog = $tgLog;
    }
}
