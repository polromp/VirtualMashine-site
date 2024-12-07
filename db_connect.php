<?php

$conn = new mysqli('localhost', 'root', 'root', 'site');

if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
