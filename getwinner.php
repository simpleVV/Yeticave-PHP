<?php

require_once('vendor/autoload.php');
require_once('init.php');
require_once('helpers.php');

$transport = new Swift_SmtpTransport(host: 'app.debugmail.io', port: '9025');
$transport->setUsername(username: '6a932bfc-f7c3-4ac8-9502-4926e1323b80');
$transport->setPassword(password: '3172e5b7-b730-490f-96f3-2b48ee3601bf');

$mailer = new Swift_Mailer($transport);

$winners = get_winners($link);

if (!empty($winners)) {
    foreach ($winners as $winner) {
        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
        $message->setTo($winner['email']);
        $msg_content = include_template('email.php', [
            'id' => $winner['id'],
            'user_name' => $winner['name'],
            'title' => $winner['title']
        ]);
        $message->setBody($msg_content, contentType: 'text/html');

        $result = $mailer->send($message);

        if (!$result) {
            $user_name = $winner['name'];
            $error_content = include_template('error.php', ['error' => "Не удалось отправить письмо пользователю $user_name"]);
        }
    }
}
