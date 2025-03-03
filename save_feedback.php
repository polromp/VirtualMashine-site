<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: loginpage.html");
    exit;
}

// Проверка, что форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback']) && isset($_POST['tema'])) {
    // Получаем данные из формы
    $feedback = trim($_POST['feedback']);
    $tema = trim($_POST['tema']);
    if (!empty($feedback)) {
        $timestamp = time();
        $filename = "FAQ/{$tema}_{$timestamp}.txt";

        file_put_contents($filename, $feedback);

        header("Location: profile.php");
        exit;
    } else {
        echo "Пожалуйста, заполните форму перед отправкой.";
    }
}
?>
