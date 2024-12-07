<?php
$term = $_GET['query'] ?? ''; // Получаем запрос от пользователя
$discipline_path = 'terms/';  // Путь к папке с дисциплинами

// Если запрос пустой, возвращаем пустой результат
if (empty($term)) {
    echo '';
    exit;
}

$files = [];

// Рекурсивный обход всех файлов в папке
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($discipline_path));

foreach ($iterator as $file) {
    // Проверяем, что это файл с расширением .txt
    if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'txt') {
        // Получаем имя файла без расширения
        $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

        // Проверяем, содержит ли имя файла термин из запроса (игнорируя регистр)
        if (stripos($filename, $term) !== false) {
            // Генерируем ссылку для каждого совпадения
            $relativePath = str_replace('\\', '/', $file->getRealPath());
            $discipline = basename(dirname(dirname($relativePath)));  // Название дисциплины
            
            $subsection = basename(dirname($relativePath));  // Название подраздела
            $termName = $filename;  // Название термина (имя файла без расширения)
            
            // Формируем ссылку на страницу с термином
            $url = "termin.php?discipline=" . urlencode( $discipline) . "&subsection=" . urlencode($subsection) . "&term=" . urlencode($termName);
            
            // Добавляем термин в список
            $files[] = "<li data-path='{$url}'>{$termName}</li>";
        }
    }
}

// Возвращаем результаты или сообщение, если ничего не найдено
if (!empty($files)) {
    echo '<ul>' . implode('', $files) . '</ul>';
} else {
    echo '<li>Термин не найден</li>';
}
?>
