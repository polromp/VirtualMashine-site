<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require 'db_connect.php';

$username = $_SESSION['username'] ?? '';

// Получаем параметры из URL
$discipline = $_GET['discipline'] ?? '';
$subsection = $_GET['subsection'] ?? '';
$term = $_GET['term'] ?? '';

$discipline_path = "terms/{$discipline}"; 

$file_path = "{$discipline_path}/{$subsection}/{$term}.txt";

if (file_exists($file_path)) {
    $file_content = file_get_contents($file_path);
} else {
    $file_content = "Определение термина не найдено.";
}

function searchTermInFiles($directory, $term) {
    $files = [];

    if (!is_dir($directory)) {
        return $files; 
    }


    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    foreach ($iterator as $file) {
        if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'txt') {

            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

            if ($filename == $term) {
                $files[] = $file->getRealPath();
            }
        }
    }
    return $files;
}

if ($discipline && is_dir($discipline_path)) {

    $subsections = array_diff(scandir($discipline_path), ['.', '..']);
} else {
    $subsections = [];
}

$terms = [];
if ($subsection && is_dir($discipline_path . '/' . $subsection)) {
    $terms = array_diff(scandir($discipline_path . '/' . $subsection), ['.', '..']);
}

function getTermDefinition($filePath) {
    if (file_exists($filePath)) {
        return file_get_contents($filePath);
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_favourites'])) {
    $check_sql = "SELECT * FROM favourites WHERE login = ? AND discipline = ? AND subsection = ? AND termin = ?";
    $stmt_check = $conn->prepare($check_sql);
    if ($stmt_check) {
        $stmt_check->bind_param('ssss', $username, $discipline, $subsection, $term);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo "<div class='alert alert-warning'>Этот термин уже добавлен в избранное.</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO favourites (login, discipline, subsection, termin) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('ssss', $username, $discipline, $subsection, $term);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Термин успешно добавлен в избранное!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Ошибка при добавлении термина в избранное.</div>";
                }
                $stmt->close();
            }
        }
        $stmt_check->close(); 
    } else {
        echo "<div class='alert alert-danger'>Ошибка при подготовке запроса.</div>";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit(); 
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
<style>
body{
background: radial-gradient(circle, #B6D0E2, #7FB3D5, #549DC7);
}
header {
    background-color: #549DC7; 
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header h1,.header1 {
    margin: 0;
    font-size: 1.8rem;
    font-family: 'Courier New', Courier, monospace;
    font-weight: bold;
    color: black;
    text-decoration: none;
}
.header1:hover{
            text-decoration: underline;
        }
header .user-info {
    display: flex;
    align-items: center;
}

header .user-icon {
    margin-right: 10px;
}
</style>
<body>
<header>
    <a class="header1" href="index.php"><h1>EnglishMath</h1></a>
    <div class="user-info">
        <?php if ($username && $term): ?>
            <form method="POST" action="" style="display: inline;">
                <input type="hidden" name="discipline" value="<?php echo htmlspecialchars($discipline); ?>">
                <input type="hidden" name="subsection" value="<?php echo htmlspecialchars($subsection); ?>">
                <input type="hidden" name="term" value="<?php echo htmlspecialchars($term); ?>">
                <button type="submit" name="add_to_favourites" class="btn p-0" style="background: none; border: none;">
                    <img src="photos/fav.png" class="user-icon" width="33" height="24" alt="Добавить в избранное">
                </button>
            </form>
        <?php else: ?>
            <a href="loginpage.html">
                <img src="photos/fav.png" class="user-icon" width="33" height="24" alt="Войдите для добавления в избранное">
            </a>
        <?php endif; ?>
        <?php if ($username): ?>
            <a href="profile.php">
                <img src="photos/icon.png" class="user-icon" width="24" height="24">
            </a>
        <?php else: ?>
            <a href="loginpage.html">
                <img src="photos/icon.png" class="user-icon" width="24" height="24">
            </a>
        <?php endif; ?>
        <span><?php echo $username ? 'Привет, ' . htmlspecialchars($username) : 'Привет, Гость'; ?></span>
    </div>
</header>

<main class="container-lg mt-4">
    <div class="row">
        <aside class="col-md-3 bg-light rounded p-3">
            <h4><?php echo htmlspecialchars($discipline); ?></h4>
            <ul class="list-group">
                <?php foreach ($subsections as $sub): ?>
                    <li class="list-group-item">
                        <a href="?discipline=<?php echo urlencode($discipline); ?>&subsection=<?php echo urlencode($sub); ?>">
                            <?php echo htmlspecialchars($sub); ?>
                        </a>
                        <?php if ($subsection && $sub === $subsection): ?>
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

        <section class="col-md-9 bg-light rounded p-3">
            <?php if ($term): ?>
                <h3><?php echo htmlspecialchars($term); ?></h3>
                <p>
                    <?php
                    $files = searchTermInFiles($discipline_path, $term);
                    if (!empty($files)) {
                        foreach ($files as $file) {
                            echo nl2br(htmlspecialchars(getTermDefinition($file)));
                        }
                    } else {
                        echo "Определение не найдено.";
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
