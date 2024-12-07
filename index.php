<?php
// Проверяем, существует ли кука 'username'
session_start();

// Получаем имя пользователя из куки
$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EnglishMath</title>
<style>
body {
    font: 16px/1.5 "Arial", sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F3F7FF; 
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

header {
    background-color: #F3F7FF; 
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
}

header h1 {
    margin: 0;
    font-size: 1.8rem;
}
/* Шапка */
header .user-info {
    display: flex;
    align-items: center;
}

header .user-icon {
  margin-right: 10px;
}

main {
    flex-grow: 1;
    padding: 20px;
}

.main-content {
    text-align: center;
    margin-bottom: 15px;
}

.main-content p {
    font-size: 1.1rem; 
    color: #333; 
    max-width: 600px;
    margin: 20px auto; 
}

.search-bar {
    margin: 0 auto;
    width: 80%;
    max-width: 600px;
    margin-bottom: 40px; /* Increased margin below search bar */
}

.search-bar input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px 0 0 5px;
}

/* Заголовок разделов */
.section-title {
    text-align: center;
    margin-bottom: 15px;
    font-size: 1.3rem;
    font-weight: bold;
}

/* Для кнопок с разделами */
.section-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    grid-gap: 20px;
    margin-bottom: 40px; /* Increased margin below sections */
}

.section-item {
    background-color: #DCFFD8; 
    padding: 30px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center; 
    justify-content: flex-start; 
    transition: background-color 0.3s ease; /* Add transition for smooth effect */
}

.section-wrapper { 
    background-color: #C0D2FF; /* background color */
    padding: 20px;
    border-radius: 10px; /* Optional: Rounded corners */
    
    margin-bottom: 20px; /* Added margin */
    
    
}

.section-item:hover {
    background-color: #74c56b; /* Lighter blue on hover */
}

.section-item img {
    width: 40px;
    height: 40px;
    margin-right: 10px;
}

/* Подвал */
footer {
    background-color: #98A1B9; 
    padding: 30px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

footer a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
}

footer a:hover {
    text-decoration: underline;
}
 /* Отступы между разделами */
.spacer {
        height: 80px; /* Adjust the height as needed */
    }
.spacer1 {
        height: 10px; /* Adjust the height as needed */
    }
</style>
</head>
<body>
    <header>
        <h1>EnglishMath</h1>
        <div class="user-info">
            <!-- Проверяем, если пользователь авторизован -->
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

    <main>
        <div class="spacer"></div>
        <div class="main-content">
            <p style="font-family: 'Garamond'; font-size: 30px; color: #38c528;"><strong>Знания — это ключ к вашему успеху, и мы поможем вам открыть все двери.</strong></p>
        </div>
        <div class="spacer1"></div>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Поиск...">
            <ul id="search-results" style="display: none; background: #fff; border: 1px solid #ccc; padding: 10px; position: absolute; width: calc(100% - 20px); max-height: 200px; overflow-y: auto;"></ul>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <div class="spacer"></div>

        <div class="section-wrapper">

        <div class="section-title"><p style="font-family: 'MS Serif'; font-size: 30px;">Разделы</p></div>
        <div class="spacer1"></div>

    <div class="section-container">
        <a href="termin.php?discipline=Math%20Analysis" style="text-decoration: none; color: inherit;">
            <div class="section-item">
                <img src="photos/analiz.png" width="40" height="40">
                <div>Math Analysis</div>
            </div>
        </a>

        <a href="termin.php?discipline=Linear%20algebra" style="text-decoration: none; color: inherit;">
            <div class="section-item">
                <img src="photos/algebra.png" width="40" height="40">
                <div>Linear algebra</div>
            </div>
        </a>
        <a href="termin.php?discipline=Analytical%20geometry" style="text-decoration: none; color: inherit;">
            <div class="section-item">
                <img src="photos/geom.png" width="40" height="40">
                <div>Analytical geometry</div>
            </div>
        </a>
        <a href="termin.php?discipline=Discrete%20mathematics" style="text-decoration: none; color: inherit;">
            <div class="section-item">
                <img src="photos/ver.png" width="40" height="40">
                <div>Discrete mathematics</div>
            </div>
        </a>
    </div>

</div>
    <div class="spacer1"></div>
        <div class="section-title"><p style="font-family: 'MS Serif'; font-size: 30px;">Полезные статьи</p></div>
        <ul>
            <li><a href="https://www.storyofmathematics.com/20th.html">The Story of Mathematics — 20th Century Mathematics</a></li>
            <li><a href="https://www.livescience.com/38936-mathematics.html">What is mathematics? | Live Science</a></li>
            <li><a href="https://www.whitman.edu/mathematics/higher_math_online/">Introduction to Higher Mathematics</a></li>
        </ul>
    </main>

    <footer>
        <div>EnglishMath</div>
        <div>
            <a href="#">Личный кабинет</a>
            <a href="#">Обратная связь</a>
        </div>
    </footer>
</body>
<script>
    $(document).ready(function () {
        // Поиск по запросу
        $('#search-input').on('input', function () {
            let query = $(this).val().trim();
            if (query.length > 0) {
                $.get('search.php', { query: query }, function (data) {
                    $('#search-results').html(data).show();
                });
            } else {
                $('#search-results').hide();
            }
        });

        // Переход по клику на элемент в результатах
        $(document).on('click', '#search-results li', function () {
            let path = $(this).data('path');
            if (path) {
                // Параметры запроса из строки (например, 'discipline=Mathematics&subsection=Linear%20Algebra&term=LinearAlgebra')
                const urlParams = new URLSearchParams(path.split('?')[1]);

                const discipline = urlParams.get('discipline');
                const subsection = urlParams.get('subsection');
                const term = urlParams.get('term');

                // Переход на нужную страницу
                window.location.href = `termin.php?discipline=${encodeURIComponent(discipline)}&subsection=${encodeURIComponent(subsection)}&term=${encodeURIComponent(term)}`;
            }
        });

        // Скрытие результатов при клике вне области поиска
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.search-bar').length) {
                $('#search-results').hide();
            }
        });
    });
</script>

</html>
