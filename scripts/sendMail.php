<?php
require_once('config-smtp.php'); //Файл конфигурации для вашего smtp сервера

require_once 'PhpMailer/src/Exception.php';
require_once 'PhpMailer/src/PHPMailer.php';
require_once 'PhpMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//////////////////////////////////////////////
//$data = file_get_contents('MailTemplate.html');
//Функция очистки от тегов и лишних пробелов
function clear_str($str) {
    return strip_tags(trim($str));
}

//Массив названия форм на сайте (id формы)
$array =    [
                'index_form'    =>      'Сообщение с сайта',
                'call_request'  =>      'Заказали звонок',
                'form_message'  =>      'Сообщение с сайта',
                'contact-form'  =>      'Сообщение с сайта',                                
            ];

if ($_POST && !empty($_POST)){
    //Очиста от тегов 
    foreach ($_POST as &$item)
        {
            $item = clear_str($item);
        }
    $title = (array_key_exists($_POST['id'], $array))?($array[$_POST['id']]):'Запрос с сайта';
    $result['name']         =   (isset($_POST['NAME']) && !empty($_POST['NAME']))?($_POST['NAME']):'Anonymous'; 
    //Проверяем наличие email адреса в форме
    $result['email_reply']  =   (isset($_POST['EMAIL']) && !empty($_POST['EMAIL']))?($_POST['EMAIL']):$smtp['addreply']; 
    $result['phone']        =   (isset($_POST['phone']) && !empty($_POST['phone']))?($_POST['phone']):'Not Phone'; 
    $result['message']      =   (isset($_POST['MESSAGE']) && !empty($_POST['MESSAGE']))?($_POST['MESSAGE']):'NotMessage';
   
    
    $body = "<!DOCTYPE html>"; // создаем тело письма
    $body .= "<html><head>"; // структуру я минимизирую, шаблонов в сети много, либо создайте свой
    $body .= "<meta charset='UTF-8' />";
    $body .= "<title>".$title."</title>";
    $body .= "</head><body>";
    $body .= "<table><tr><td>";
    $body .= "<table style='width:600px'><tr><td>";
    $body .= "<tr><td ><h3 style='text-align:center'>".$title."</h3></td></tr>"; // вывели в тело имя пользователя и само сообщение с ссылкой для отписки

            foreach ($result as $key => $value) {
                $body .= "<tr><td><h4>".ucfirst($key).":</h4></td><td>".nl2br($value)."</td></tr>"; // вывели в тело имя пользователя и само сообщение с ссылкой для отписки
            } 
            
    $body .= "<tr><td style='text-align:center'><em>All rights reserved | Copyright &copy; ".date("d-m-Y")."</em></td></tr>";
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
        $mail->AddReplyTo($result['email_reply'], $result['name']);  // Альтернативный адрес для ответа
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

