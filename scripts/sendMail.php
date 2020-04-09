<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header('Location: /404/');
    exit;
}
require_once('config-smtp.php'); //Файл конфигурации для вашего smtp сервера

require_once 'PhpMailer/src/Exception.php';
require_once 'PhpMailer/src/PHPMailer.php';
require_once 'PhpMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//////////////////////////////////////////////
//Функция очистки от тегов и лишних пробелов
function clear_str($str) {
    return strip_tags(trim($str));
}

//Массив названия форм на сайте (id формы)
$array = require_once 'config-form.php';
if ($_POST && !empty($_POST)){
    //Очиста от тегов 
    foreach ($_POST as &$item)
        {
            $item = clear_str($item);
        } 

// Формируем двухмерный массив, в котором каждый массив имеет два поля: имя поля и значение
    $i = 0;  
    if (array_key_exists($_POST['id'], $array)) {       
        $title = (isset($array[$_POST['id']]['title']) && !empty($array[$_POST['id']]['title']))?$array[$_POST['id']]['title']:'Сообщение с сайта';
        foreach ($array[$_POST['id']]['fields'] as $key=>$item) {  
                foreach ($_POST as $keys=>$post) {
                    if (strtolower($keys) === $key) 
                        {                       
                                $result[$i]['name'] = $item;
                                $result[$i]['value'] = $post;
                                $i++;
                            break;
                        }
            }
        }
    }else {
        $title = 'Сообщение с сайта';
        foreach ($_POST as $keys=>$post) {
              $result[$i]['name'] = $keys;
              $result[$i]['value'] = $post;
            $i++; 
        }
    }    
  
    $email_reply =   (isset($_POST['EMAIL']) && !empty($_POST['EMAIL']))?($_POST['EMAIL']):$smtp['addreply']; 
    
    $body = "<!DOCTYPE html>"; // создаем тело письма
    $body .= "<html><head>"; // структуру я минимизирую, шаблонов в сети много, либо создайте свой
    $body .= "<meta charset='UTF-8' />";
    $body .= "<title>".$title."</title>";
    $body .= "</head><body>";
    $body .= "<table><tr><td>";
    $body .= "<table style='width:600px; border-spacing: 10px; border: 1px solid silver; padding: 10px;'><tr><td>";
    $body .= "<tr><td ><h3 style='text-align:center; border-bottom: 1px solid silver; color:#82b3f9;'>".$title."</h3></td></tr>"; 
            foreach ($result as $value) {               
                $body .= "<tr><td><strong>".ucfirst($value['name']).":</strong> ".nl2br($value['value'])."</td></tr>"; 
            } 
    $body .= "<tr><td></td></tr>"; 
    $body .= "<tr style='cellpadding: 10px;'><td style='text-align:center; border-top: 1px solid silver;'><em>All rights reserved | Copyright &copy; Atlas&Comp ".date("d-m-Y")."</em></td></tr>";
    $body .= "</table></td></tr></table>";
    $body .= "</body></html>";     
}

try{
        $mail = new PHPMailer(true); // Создаем экземпляр класса PHPMailer
        $mail->IsSMTP(); // Указываем режим работы с SMTP сервером
        $mail->Host       = $smtp['host'];  // Host SMTP сервера: ip или доменное имя
        $mail->SMTPDebug  = $smtp['debug'];  // Уровень журнализации работы SMTP клиента PHPMailer
        $mail->SMTPAuth   = $smtp['auth'];  // Наличие авторизации на SMTP сервере
        $mail->Port       = $smtp['port'];  // Порт SMTP сервера
        $mail->SMTPSecure = $smtp['secure'];  // Тип шифрования. Например ssl или tls
        $mail->CharSet="UTF-8";  // Кодировка обмена сообщениями с SMTP сервером
        $mail->Username   = $smtp['username'];  // Имя пользователя на SMTP сервере
        $mail->Password   = $smtp['password'];  // Пароль от учетной записи на SMTP сервере
        $mail->AddAddress ($smtp['addAddress'], 'W');  // Адресат почтового сообщения
        $mail->AddReplyTo($email_reply, 'Client');  // Альтернативный адрес для ответа
        $mail->SetFrom($smtp['username'], $smtp['mail_name']);  // Адресант почтового сообщения
        $mail->Subject = htmlspecialchars($title);  // Тема письма
        $mail->MsgHTML($body); // Текст сообщения
        
        if ($mail->send()) {
            echo json_encode([
                                'status'=> true,
                                'message' => 'Ожидайте звонка Пожалуйста'
                            ]);
        }else {
            echo json_encode([
                                'status'=> false,
                                'message' => 'Ошибка! Попробуйте позже'
                            ]);
        }; 
  } 
  
catch (phpmailerException $e) {
    echo $e->errorMessage();
}
?>

