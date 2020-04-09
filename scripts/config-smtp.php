<?php

$smtp = array(
    "host" => 'smtp.yandex.ru', // SMTP сервер
    "debug" => 0, // Уровень логирования
    "auth" => true, // Авторизация на сервере SMTP. Если ее нет - false
    "port" => '465', // Порт SMTP сервера
    "username" => 'nkn-relay@yandex.ru', // Логин запрашиваемый при авторизации на SMTP сервере
    "password" => '00nkn00qwerty', // Пароль
    "addreply" => 'nkn-relay@yandex.ru', // Почта для ответа
    "secure" => 'ssl', // Тип шифрования. Например ssl или tls
    "mail_title" => 'Заголовок вашего письма!', // Заголовок письма
    "mail_name" => 'Nkn-Мебель', // Имя отправителя
    "addAddress" => 'doroshuk89@gmail.com' //Адресс получателя
);

