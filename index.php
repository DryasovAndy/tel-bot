<script>
    setTimeout(function () {
        window.location.reload();
    }, 10 * 60 * 1000);
    console.log(new Date());
</script>

<?php

require 'vendor/autoload.php';
require 'ConnectionService.php';

use Service\ConnectionService;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;

$telegram = new Api(getenv("TELEGRAM_API"));

$result = $telegram->getWebhookUpdate();

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Срочно нужна причина для отмазки"]]; //Клавиатура
$brokeBackMountain = 'https://avatars.mds.yandex.net/get-ott/1531675/2a00000176680c1e3250d9adabbd157aa3d0/1344x756';
$dildo = 'https://www.sexsoshop.ru/img/tovars/LoveToy/2660010001961-1.jpg';

$connectionService = new ConnectionService();
$pdo = $connectionService->createNewConnection();
$lastCommand = $connectionService->getLastCommand($pdo);

if ($text && $chat_id) {
    if ($text === "/start") {
        $connectionService->updateLastCommand($pdo, $text);
        $reply = "Привет. Меня зовут Олег и я опять решил проебаться";
        $reply_markup = Keyboard::make(
            ['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false]
        );
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
    } elseif ($text === "/bro") {
        $connectionService->updateLastCommand($pdo, $text);
        $telegram->sendPhoto(['chat_id' => $chat_id, 'photo' => InputFile::create($brokeBackMountain)]);
    } elseif ($text === "/hui") {
        $connectionService->updateLastCommand($pdo, $text);
        $telegram->sendPhoto(['chat_id' => $chat_id, 'photo' => InputFile::create($dildo)]);
    } elseif ($text === "Срочно нужна причина для отмазки") {
        $connectionService->updateLastCommand($pdo);
        $reason = $connectionService->getRandomReasonForExcuse($pdo);
        $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => $reason]);
    } elseif ($text === "/add") {
        $reply = "Да, добавь еще одну";
        $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => $reply]);
        $connectionService->updateLastCommand($pdo, "/add");
    } elseif ($lastCommand === "/add") {
        $connectionService->addNewReason($pdo, $text);
        $connectionService->updateLastCommand($pdo);
        $reply = "Commander, new reason was approved";
        $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => $reply]);
    } elseif ($text === "/show") {
        $reply = "Вспомни все свои грехи";
        $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => $reply]);
        $allReasons = $connectionService->getAllReasonsForExcuse($pdo);


        $output = implode(", ", array_map(
            static function ($key, $value) { return "[$key] $value /n"; },
            array_keys($allReasons),array_values($allReasons)));

        $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => $output]);
        $connectionService->updateLastCommand($pdo);
    } else {
        $reply = "Тупо тыкай кнопку. Здесь нет дополнительного функционала";
        $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => $reply]);
    }
} else {
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение."]);
}
