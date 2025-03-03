<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_path'])) {
    $file_path = $_POST['file_path'];

    if (file_exists($file_path) && strpos(realpath($file_path), realpath('FAQ')) === 0) {
        if (unlink($file_path)) {
            echo json_encode(['status' => 'success', 'message' => 'Файл успешно удален']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка при удалении файла']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Файл не найден или неверный путь']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Неправильный запрос']);
}
?>
