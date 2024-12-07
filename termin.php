<?php
// Получаем параметры из URL
ini_set('display_errors', 1);
error_reporting(E_ALL);

$discipline = $_GET['discipline'] ?? '';
$subsection = $_GET['subsection'] ?? '';
$term = $_GET['term'] ?? '';
// Проверяем, если дисциплина задана, то формируем путь
if ($discipline) {
    $discipline_path = 'terms/' . $discipline;
} else {
    $discipline_path = '';
}

// Функция для поиска термина среди всех файлов в подкатегориях
function searchTermInFiles($directory, $term) {
    $files = [];
    
    // Проверка на существование директории
    if (!is_dir($directory)) {
        return $files; // Возвращаем пустой массив, если папки не существует
    }
    
    // Рекурсивный обход файлов и папок
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    foreach ($iterator as $file) {
        if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'txt') {
            // Получаем имя файла без расширения
            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
    
            // Проверка совпадения имени файла с термином
            if ($filename == $term) {
                $files[] = $file->getRealPath();
            }
        }
    }
    return $files;
}

// Проверяем, существует ли папка дисциплины
if ($discipline && is_dir($discipline_path)) {
    // Получаем подразделы, если есть
    $subsections = array_diff(scandir($discipline_path), ['.', '..']);
} else {
    $subsections = [];
}

// Получаем термины, если указан подраздел
$terms = [];
if ($subsection && is_dir($discipline_path . '/' . $subsection)) {
    // Получаем все файлы в подразделе
    $terms = array_diff(scandir($discipline_path . '/' . $subsection), ['.', '..']);
}

// Функция для получения определения термина из файла
function getTermDefinition($filePath)
{
    if (file_exists($filePath)) {
        return file_get_contents($filePath);
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnglishMath</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-lg">
                <a class="navbar-brand" href="index.php">EnglishMath</a>
                <div class="d-none d-lg-block">
                    <form class="d-flex mb-0">
                        <input class="form-control me-2" type="search" placeholder="Поиск" aria-label="Search">
                        <button class="btn btn-outline-success me-4" type="submit">Искать</button>
                    </form>
                </div>
                <div class="nav">
                    <a class="nav-link" href="#"><i class="bi bi-bookmark-star-fill"></i></a>
                    <a class="nav-link" href="#"><i class="bi bi-person-circle"></i></a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container-lg mt-4">
        <div class="row">
            <!-- Левый блок с подразделами и терминами -->
            <aside class="col-md-3 bg-light rounded p-3">
                <h4><?php echo htmlspecialchars($discipline); ?></h4>
                <ul class="list-group">
                    <?php foreach ($subsections as $sub): ?>
                        <li class="list-group-item">
                            <a href="?discipline=<?php echo urlencode($discipline); ?>&subsection=<?php echo urlencode($sub); ?>">
                                <?php echo htmlspecialchars($sub); ?>
                            </a>

                            <?php if ($subsection && $sub === $subsection): ?>
                                <!-- Если выбран текущий подраздел, выводим термины -->
                                <ul class="list-group mt-2">
                                    <?php foreach ($terms as $term_item): ?>
                                        <li class="list-group-item">
                                            <a href="?discipline=<?php echo urlencode($discipline); ?>&subsection=<?php echo urlencode($subsection); ?>&term=<?php echo urlencode(pathinfo($term_item, PATHINFO_FILENAME)); ?>">
                                                <?php echo htmlspecialchars(pathinfo($term_item, PATHINFO_FILENAME)); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <!-- Основная область для отображения определения термина -->
            <section class="col-md-9 bg-light rounded p-3">
                <?php if ($term): ?>
                    <h3><?php echo htmlspecialchars($term); ?></h3>
                    <p>
                        <?php
                        // Проверка на пустой термин
                        if ($term) {
                            // Поиск термина среди всех файлов
                            $files = searchTermInFiles($discipline_path, $term);
                            if (!empty($files)) {
                                foreach ($files as $file) {
                                    echo nl2br(htmlspecialchars(getTermDefinition($file)));
                                }
                            } else {
                                echo "Определение не найдено.";
                            }
                        } else {
                            echo "Термин не выбран.";
                        }
                        ?>
                    </p>
                <?php elseif ($subsection): ?>
                    <h3>Выберите термин в подразделе "<?php echo htmlspecialchars($subsection); ?>"</h3>
                <?php else: ?>
                    <p>Выберите подраздел и термин для просмотра их определения.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
