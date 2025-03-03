<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db_connect.php'; 

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "UPDATE user SET verified = 1, verification_token = NULL WHERE verification_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token); 

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "Ваш email успешно подтверждён! Теперь вы можете войти в систему.";
        
        header("refresh:3;url=loginpage.html"); 
        exit();
    } else {
        echo "Неверный или устаревший токен.";
    }

    $stmt->close();
} else {
    echo "Токен не указан.";
}
?>
