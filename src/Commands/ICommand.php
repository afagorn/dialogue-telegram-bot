<?php
namespace afagorn\DialogueTelegramBot\Commands;

interface ICommand
{
    /**
     * Имя команды без слеша
     */
    function setName();

    function handle(string $param = '');
}
