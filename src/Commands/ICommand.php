<?php
namespace afagorn\DialogueTelegramBotSDK\Commands;

interface ICommand
{
    /**
     * Имя команды без слеша
     */
    function setName();

    function handle(string $param = '');
}
