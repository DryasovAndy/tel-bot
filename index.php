<script>
    setTimeout(function () { window.location.reload(); }, 10*60*1000);
    console.log(new Date());
</script>

<?php

require 'vendor/autoload.php';
require_once 'connection.php';

use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;

$telegram = new Api('1735568884:AAHwl4IOTJSkdtaQx_nCrWWOE4WMSVn-1fE');
$url = 'https://excuse-telegram-bot.herokuapp.com'; // URL RSS feed

$result = $telegram->getWebhookUpdates();

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Срочно нужна причина для отмазки"]]; //Клавиатура
$brokeBackMountain = 'https://avatars.mds.yandex.net/get-ott/1531675/2a00000176680c1e3250d9adabbd157aa3d0/1344x756';
$dildo = 'https://www.sexsoshop.ru/img/tovars/LoveToy/2660010001961-1.jpg';

$sql = 'select r.reason
from d56dm3jpas8cjd.public.reasons r
ORDER BY RANDOM()
LIMIT 1
';

$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::PARAM_STR);

$lines = file('./reasons.txt');
$reasons = [];

foreach ($lines as $line_num => $line) {
    $reasons[] = $line;
}

if ($text) {
    if ($text === "/start") {
        $reply = "Привет. Меня зовут Олег и я опять решил проебаться";
        $reply_markup = Keyboard::make(
            ['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false]
        );
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
    } elseif ($text === "/bro") {
        $telegram->sendPhoto(['chat_id' => $chat_id, 'photo' => InputFile::create($brokeBackMountain)]);
        $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => '1']);
    } elseif ($text === "/hui") {
        $telegram->sendPhoto(['chat_id' => $chat_id, 'photo' => InputFile::create($dildo)]);
    } elseif ($text === "Срочно нужна причина для отмазки") {
        $key = array_rand($reasons);
        $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => $reasons[$key]]);
    } else {
        $reply = "Тупо тыкай кнопку. Здесь нет дополнительного функционала";
        $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => $reply]);
    }
} else {
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение."]);
}
