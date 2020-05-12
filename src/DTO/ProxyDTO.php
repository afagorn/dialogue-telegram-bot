<?php
namespace DialogueTelegramBotSDK\DTO;

class ProxyDTO
{
    /**
     * @var string
     */
    public $ip = '';

    /**
     * @var string
     */
    public $port = '';

    /**
     * @var string
     */
    public $login = '';

    /**
     * @var string
     */
    public $password = '';

    /**
     * ProxyDTO constructor.
     * @param string $ip
     * @param string $port
     * @param string $login
     * @param string $password
     */
    public function __construct(string $ip, string $port, string $login = '', string $password = '')
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->login = $login;
        $this->password = $password;
    }

    public function getSocksUrl()
    {
        return "socks5://$this->login:$this->password@$this->ip:$this->port";
    }
}
