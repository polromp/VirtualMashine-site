<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db_connect.php'; 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === 'admin') {
        $sql = "SELECT * FROM admin WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin['password'])) {
                $_SESSION['user_id'] = $admin['id']; 
                $_SESSION['username'] = $admin['login']; 

                setcookie('username', $admin['login'], time() + 1 * 24 * 60 * 60, '/', '', isset($_SERVER["HTTPS"]), true);
                setcookie('user_id', $admin['id'], time() + 1 * 24 * 60 * 60, '/', '', isset($_SERVER["HTTPS"]), true);

                header('Location: admin.php');
                exit();
            } else {
                echo "Неверный пароль для администратора.";
            }
        } else {
            echo "Пользователь с таким логином не найден.";
        }

    } else {
        $sql = "SELECT * FROM user WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($user['verified'] == 0) {
                echo "Пожалуйста, подтвердите ваш email перед входом.";
            } else {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['login'];  

                    setcookie('username', $user['login'], time() + 1 * 24 * 60 * 60, '/', '', isset($_SERVER["HTTPS"]), true);
                    setcookie('user_id', $user['id'], time() + 1 * 24 * 60 * 60, '/', '', isset($_SERVER["HTTPS"]), true);

                    header('Location: index.php');
                    exit();
                } else {
                    echo "Неверный пароль.";
                }
            }
        } else {
            echo "Пользователь с таким логином не найден.";
        }
    }

    $stmt->close();
}
?>
