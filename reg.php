<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']); 
    $lastName = trim($_POST['lastname']); 
    $username = trim($_POST['login']); 
    $password = $_POST['password']; 
    $email = trim($_POST['email']); 
    $confirmPassword = $_POST['confirm_password']; 

    if ($password !== $confirmPassword) {
        die('Пароли не совпадают.');
    }


    $checkUserSql = "SELECT * FROM user WHERE email = ? OR login = ?";
    $stmt = $conn->prepare($checkUserSql);
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die('Пользователь с таким email или логином уже существует.');
    }


    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


    $verificationToken = bin2hex(random_bytes(32));

    $sql = "INSERT INTO user (name, lastname, login, password, email, verification_token) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $name, $lastName, $username, $hashedPassword, $email, $verificationToken);

    if ($stmt->execute()) {
        $verificationLink = "http://localhost:8888/verify_email.php?token=" . $verificationToken;
    
        $mail = new PHPMailer(true);
    
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ayaz240404@gmail.com'; 
            $mail->Password = 'rkhg qgoj sasj csbo'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('ayaz240404@gmail.com', 'EnglishMath');
            $mail->addAddress($email, $name); 
    
            $mail->CharSet = 'UTF-8';

            $mail->isHTML(true);
            $mail->Subject = "Подтверждение email - EnglishMath";
            $mail->Body = "Здравствуйте, $name!<br><br>Для подтверждения вашего email, пожалуйста, перейдите по ссылке:<br><a href='$verificationLink'>$verificationLink</a><br><br>С уважением,<br>Команда EnglishMath";

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
