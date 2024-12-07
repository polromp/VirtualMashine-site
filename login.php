<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db_connect.php'; // Подключаем файл с соединением с БД

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Проверяем, существует ли пользователь с таким логином
    $sql = "SELECT * FROM user WHERE login = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Пользователь найден, проверяем пароль
        $user = $result->fetch_assoc();

        // Проверяем, подтвержден ли email
        if ($user['verified'] == 0) {
            echo "Пожалуйста, подтвердите ваш email перед входом.";
        } else {
            // Проверяем пароль
            if (password_verify($password, $user['password'])) {
                // Авторизация прошла успешно
                $_SESSION['user_id'] = $user['id'];  // Сохраняем ID пользователя в сессии
                $_SESSION['username'] = $user['login'];  // Сохраняем логин в сессии

                // Устанавливаем куки на 30 дней для автоматического входа
                setcookie('username', $user['login'], time() + 1 * 24 * 60 * 60, '/', '', isset($_SERVER["HTTPS"]), true);
                setcookie('user_id', $user['id'], time() + 1 * 24 * 60 * 60, '/', '', isset($_SERVER["HTTPS"]), true);

                // Перенаправляем на главную страницу
                header('Location: index.php');
                exit();
            } else {
                echo "Неверный пароль.";
            }
        }
    } else {
        echo "Пользователь с таким логином не найден.";
    }

    $stmt->close();
}
?>
