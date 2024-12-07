<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db_connect.php'; // Подключаем файл с соединением с БД

// Проверяем, есть ли токен в URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Проверяем, существует ли такой токен в базе данных и обновляем статус пользователя
    $sql = "UPDATE user SET verified = 1, verification_token = NULL WHERE verification_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token); // Привязываем токен из URL

    // Выполняем запрос
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // Если токен совпал, выводим сообщение об успешной регистрации
        echo "Ваш email успешно подтверждён! Теперь вы можете войти в систему.";
        
        // Перенаправляем на страницу авторизации через 3 секунды
        header("refresh:3;url=loginpage.html"); 
        exit();
    } else {
        // Если токен не найден или устарел
        echo "Неверный или устаревший токен.";
    }

    $stmt->close();
} else {
    echo "Токен не указан.";
}
?>
