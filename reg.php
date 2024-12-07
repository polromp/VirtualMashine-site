<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = trim($_POST['name']); // Имя
    $lastName = trim($_POST['lastname']); // Фамилия
    $username = trim($_POST['login']); // Логин
    $password = $_POST['password']; // Пароль
    $email = trim($_POST['email']); // Электронная почта
    $confirmPassword = $_POST['confirm_password']; // Подтверждение пароля

    // Проверяем совпадение паролей
    if ($password !== $confirmPassword) {
        die('Пароли не совпадают.');
    }

    // Проверяем, существует ли уже такой email или логин
    $checkUserSql = "SELECT * FROM user WHERE email = ? OR login = ?";
    $stmt = $conn->prepare($checkUserSql);
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die('Пользователь с таким email или логином уже существует.');
    }

    // Хэшируем пароль
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Генерируем токен подтверждения
    $verificationToken = bin2hex(random_bytes(32));

    // Вставляем пользователя в базу данных
    $sql = "INSERT INTO user (name, lastname, login, password, email, verification_token) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $name, $lastName, $username, $hashedPassword, $email, $verificationToken);

    if ($stmt->execute()) {
        // Отправляем email с ссылкой на подтверждение
        $verificationLink = "http://localhost:8888/verify_email.php?token=" . $verificationToken;
    
        $mail = new PHPMailer(true);
    
        try {
            // Настройки SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP сервер Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'ayaz240404@gmail.com'; // Ваш Gmail адрес
            $mail->Password = 'rkhg qgoj sasj csbo'; // Пароль Gmail или пароль приложения
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            // Адрес и отправитель
            $mail->setFrom('ayaz240404@gmail.com', 'MathMate');
            $mail->addAddress($email, $name); // Адрес получателя
    
            // Устанавливаем кодировку
            $mail->CharSet = 'UTF-8';
    
            // Контент письма
            $mail->isHTML(true);
            $mail->Subject = "Подтверждение email - MathMate";
            $mail->Body = "Здравствуйте, $name!<br><br>Для подтверждения вашего email, пожалуйста, перейдите по ссылке:<br><a href='$verificationLink'>$verificationLink</a><br><br>С уважением,<br>Команда MathMate";
    
            // Отправляем письмо
            $mail->send();
            echo "<p>Регистрация прошла успешно. Проверьте ваш email для подтверждения.</p>";
            echo "<p>Если письмо не пришло, проверьте папку <strong>Спам</strong>.</p>";
        } catch (Exception $e) {
            echo "<p>Ошибка при отправке письма: {$mail->ErrorInfo}</p>";
        }
    }
    

    $stmt->close();
}
?>
