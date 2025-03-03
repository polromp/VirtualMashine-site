<?php
$base_dir = 'terms/';

$section = $_GET['section'] ?? '';
$subsection = $_GET['subsection'] ?? '';
$term = $_GET['term'] ?? '';

function listDirectories($path) {
    return array_filter(scandir($path), function ($item) use ($path) {
        return $item !== '.' && $item !== '..' && is_dir($path . '/' . $item);
    });
}

function listFiles($path) {
    return array_filter(scandir($path), function ($item) use ($path) {
        return $item !== '.' && $item !== '..' && is_file($path . '/' . $item);
    });
}

$subsections = $section ? listDirectories($base_dir . $section) : [];
$terms = ($section && $subsection) ? listFiles($base_dir . $section . '/' . $subsection) : [];

$file_content = '';
if ($section && $subsection && $term) {
    $file_path = "{$base_dir}{$section}/{$subsection}/{$term}.txt";
    if (file_exists($file_path)) {
        $file_content = file_get_contents($file_path);
    } else {
        $file_content = "Определение термина не найдено.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_term'])) {
    $term_name = trim($_POST['term_name']);
    $term_content = trim($_POST['term_content']);

    if ($section && $subsection && $term_name) {
        $directory = "{$base_dir}{$section}/{$subsection}";
        $file_path = "{$directory}/" . basename($term_name) . ".txt";

        if (file_put_contents($file_path, $term_content) !== false) {
            echo "<div class='alert alert-success' id='success-message'>Термин успешно добавлен!</div>";
        } else {
            echo "<div class='alert alert-danger'>Ошибка при создании файла.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Пожалуйста, выберите раздел и подраздел для добавления термина.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_term'])) {
    $term_content = trim($_POST['term_content']);
    if ($section && $subsection && $term) {
        $file_path = "{$base_dir}{$section}/{$subsection}/{$term}.txt";

        if (file_put_contents($file_path, $term_content) !== false) {
            $file_content = $term_content;
            echo "<div class='alert alert-success' id='success-message'>Термин успешно обновлен!</div>";
        } else {
            echo "<div class='alert alert-danger'>Ошибка при обновлении термина.</div>";
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_subsection'])) {
  $term_subsection = trim($_POST['subsection_name']);
  
  $directory = "{$base_dir}{$section}";
  $new_subsection_path = "{$directory}/" . basename($term_subsection);

  if (!file_exists($new_subsection_path)) {
      if (mkdir($new_subsection_path, 0777, true)) {
          echo "<div class='alert alert-success'>Подраздел успешно добавлен!</div>";
      } else {
          echo "<div class='alert alert-danger'>Ошибка при создании подраздела.</div>";
      }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body{
            background: radial-gradient(circle, #B6D0E2, #7FB3D5, #549DC7);
        }
        header {
            background-color: #F3F7FF;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar {
            max-height: 80vh;
            overflow-y: auto;
        }
        .button-add {
            border: 1px;
            border-radius: 10px;
            width: 100%;
            background-color: #ADD8E6;
            padding: 10px;
        }
        .button-add:hover {
            background-color: #4169E1;
        }
        .alert-success {
          position: absolute;
          bottom: 0;
        }
        #faq-content{
          height: 250px;
          width: 100%;
          border: 1px solid #4169E1;
          border-radius: 10px;
        }
        .faq-btn{
          margin-bottom: 7px;
        }
        .head{
          display: flex;
        }
        header {
            background-color: #549DC7; 
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0px;
            width: 100%;
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }
        .logo{
            color: black;
            text-decoration: none;
            
        }
        .logo:hover{
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo"><h1>EnglishMath</h1></a> 
        <div class="form-group">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal4">Посмотреть FAQ</button>
            <a href="logout.php" class="btn btn-danger">Выйти</a>
        </div>

        <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel4" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel4">FAQ</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <?php
                      $faqDir = 'FAQ';

                      if (is_dir($faqDir)) {
                          $faqFiles = glob($faqDir . '/*.txt');

                          if (!empty($faqFiles)) {
                              echo '<ul class="list-group">';
                              foreach ($faqFiles as $file) {
                                  $fileName = basename($file, '.txt');
                                  echo '<button type="button" class="btn btn-primary faq-btn" data-bs-toggle="modal" data-bs-target="#exampleModal5" data-faq-file="' . htmlspecialchars($file) . '">' . htmlspecialchars($fileName) . '</button>';
                              }
                              echo '</ul>';
                          } else {
                              echo '<p>Нет доступных FAQ.</p>';
                          }
                      } else {
                          echo '<p>Папка FAQ не найдена.</p>';
                      }
                      ?>
                  </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal5" tabindex="-1" aria-labelledby="exampleModalLabel5" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Содержимое FAQ</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="faq-content">Загрузка содержимого...</textarea>
                    <button type="button" class="btn btn-danger" id="delete-faq-btn">Удалить</button>

                </div>
            </div>
        </div>
    </div>

    </header>

    <main class="container-lg mt-4">
        <div class="row">
            <aside class="col-md-3 sidebar">
                <h4>Разделы</h4>
                <ul class="list-group">
                    <?php
                    $sections = listDirectories($base_dir);
                    foreach ($sections as $sec): ?>
                        <li class="list-group-item">
                            <a href="?section=<?php echo urlencode($sec); ?>"><?php echo htmlspecialchars($sec); ?></a>
                            <?php if ($section === $sec): ?>
                                <ul class="list-group mt-2">
                                    <?php foreach ($subsections as $sub): ?>
                                        <li class="list-group-item">
                                            <a href="?section=<?php echo urlencode($section); ?>&subsection=<?php echo urlencode($sub); ?>">
                                                <?php echo htmlspecialchars($sub); ?>
                                            </a>
                                            <?php if ($subsection === $sub): ?>
                                                <ul class="list-group mt-2">
                                                    <?php foreach ($terms as $t): ?>
                                                        <li class="list-group-item">
                                                            <a href="?section=<?php echo urlencode($section); ?>&subsection=<?php echo urlencode($subsection); ?>&term=<?php echo urlencode(pathinfo($t, PATHINFO_FILENAME)); ?>">
                                                                <?php echo htmlspecialchars(pathinfo($t, PATHINFO_FILENAME)); ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <div class="button-container mt-2">
                                                    <button class="button-add" id="add-term-btn">Добавить термин</button>
                                                </div>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="button-container mt-2">
                                  <button class="button-add" id="add-subsection-btn">Добавить подраздел</button>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <section class="col-md-9">
                <?php if ($section && $subsection && $term): ?>
                    <h3>Содержимое термина: <?php echo htmlspecialchars($term); ?></h3>
                    <div class="border p-3 rounded bg-light" id="col-md-9">
                        <p><?php echo nl2br(htmlspecialchars($file_content)); ?></p>
                        <button class="btn btn-secondary btn-sm mt-2" onclick="editTerm('<?php echo htmlspecialchars(pathinfo($t, PATHINFO_FILENAME)); ?>')">Редактировать</button>
                    </div>
                    

                    <form id="edit-term-form" method="POST" action="" style="display: none;">
                        <textarea class="form-control" name="term_content" rows="4"><?php echo htmlspecialchars($file_content); ?></textarea>
                        <button type="submit" class="btn btn-primary mt-2" name="edit_term">Сохранить изменения</button>
                    </form>
                <?php elseif ($section && $subsection): ?>
                    <h3>Выберите термин в подразделе "<?php echo htmlspecialchars($subsection); ?>"</h3>
                <?php elseif ($section): ?>
                    <h3>Выберите подраздел в разделе "<?php echo htmlspecialchars($section); ?>"</h3>
                <?php else: ?>
                    <h3>Выберите раздел для просмотра терминов</h3>
                <?php endif; ?>
                <div id="add-term-form" class="mt-3" style="display: none;">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="term-name" class="form-label">Название термина</label>
                            <input type="text" class="form-control" id="term-name" name="term_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="term-content" class="form-label">Содержимое термина</label>
                            <textarea class="form-control" id="term-content" name="term_content" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit_term">Добавить</button>
                    </form>
                </div>

                <div id="add-subsection-form" class="mt-3" style="display: none;">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="subsection-name" class="form-label">Название подраздела</label>
                            <input type="text" class="form-control" id="subsection-name" name="subsection_name" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit_subsection">Добавить</button>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.getElementById('add-subsection-btn').addEventListener('click', function () {
            document.getElementById('add-subsection-form').style.display = 'block';
            document.getElementById('add-term-form').style.display = 'none'; 
            document.getElementById('col-md-9').style.display = 'none';
            document.getElementById('edit-term-form').style.display = 'none';
        });

        document.getElementById('add-term-btn').addEventListener('click', function () {
            document.getElementById('add-term-form').style.display = 'block';
            document.getElementById('add-subsection-form').style.display = 'none';
            document.getElementById('col-md-9').style.display = 'none';
            document.getElementById('edit-term-form').style.display = 'none';
        });

        function editTerm(term) {
            document.getElementById('edit-term-form').style.display = 'block';
            document.getElementById('col-md-9').style.display = 'none';
        }

        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 2000);
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.faq-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const filePath = this.getAttribute('data-faq-file'); 

                fetch(filePath)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Ошибка загрузки файла');
                        }
                        return response.text(); 
                    })
                    .then(data => {
                        document.getElementById('faq-content').textContent = data;
                    })
                    .catch(error => {
                        document.getElementById('faq-content').textContent = 'Ошибка при загрузке содержимого.';
                        console.error(error);
                    });
            });
        });
    });
    document.getElementById('delete-faq-btn').addEventListener('click', function () {
    const filePath = document.querySelector('.faq-btn[data-bs-toggle="modal"][data-bs-target="#exampleModal5"]').getAttribute('data-faq-file');

    if (confirm('Вы уверены, что хотите удалить этот файл?')) {
        fetch('delete_faq.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'file_path=' + encodeURIComponent(filePath),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.querySelector('#exampleModal5 .btn-close').click();
                setTimeout(() => location.reload(), 500);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Ошибка при удалении файла');
        });
    }
});

</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
