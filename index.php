<?php
session_start();

if (isset($_COOKIE['username'])) {
    if ($_COOKIE['username'] == "admin") {
        $username = "admin";
    } else {
        $username = $_COOKIE['username']; 
    }
} else {
    $username = null; 
}

//$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : $_COOKIE['username'] == "admin" ? "admin" : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EnglishMath</title>
<style>
body{
    background: radial-gradient(circle, #B6D0E2, #7FB3D5, #549DC7);
}
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
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
    margin-bottom: 40px;
}

.search-bar input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px 0 0 5px;
}

.section-title {
    text-align: center;
    margin-bottom: 15px;
    font-size: 1.3rem;
    font-weight: bold;
}

.section-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    grid-gap: 20px;
    margin-bottom: 40px; 
}

.section-item {
    background-color: #DCFFD8; 
    padding: 30px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center; 
    justify-content: flex-start; 
    transition: background-color 0.3s ease; 
}

.section-wrapper { 
    background-color: #B6D0E2; 
    padding: 20px;
    border-radius: 10px;
    
    margin-bottom: 20px; 
    
    
}

.section-item:hover {
    background-color: #74c56b; 
}

.section-item img {
    width: 40px;
    height: 40px;
    margin-right: 10px;
}

.spacer {
        height: 80px;
    }
.spacer1 {
        
        height: 10px; 
        

    }
.spacer2 {
    margin-top: 70px;
    background-color: #B6D0E2;
    border: 1px;
    border-radius: 10px;
    text-align: center;
    
}
.mater-conteiner{
    display: grid; 
    grid-template-columns: 30% 30% 30%;
    justify-items: center;
    justify-content: center;
}
.mater{
    color: blue;
    align-content: center;
    margin: 10px;
    border: black;
    width: 300px;
    margin-bottom: 10px;
    background-color: #DCFFD8;
    border-radius: 10px;
}
</style>
</head>
<body>
    <header>
        <h1>EnglishMath</h1>
        <div class="user-info">
            <?php if ($username == "admin"): ?>
                <a href="admin.php">
                    <img src="photos/icon.png" class="user-icon" width="24" height="24">
                </a>
            <?php elseif ($username): ?>
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
            <p style="font-family: 'Garamond'; font-size: 30px; color: black;"><strong>Знания — это ключ к вашему успеху, и мы поможем вам открыть все двери.</strong></p>
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
    <div class="spacer2">
        <div class="section-title"><p style="font-family: 'MS Serif'; font-size: 30px;">Полезные статьи</p></div>
            <div class="mater-conteiner">
                <a class="mater" href="https://www.storyofmathematics.com/20th.html">The Story of Mathematics — 20th Century Mathematics</a>
                <a class="mater" href="https://www.livescience.com/38936-mathematics.html">What is mathematics? | Live Science</a>
                <a class="mater" href="https://www.whitman.edu/mathematics/higher_math_online/">Introduction to Higher Mathematics</a>
                <a class="mater" href="https://mathvault.ca/hub/higher-math/">Foundation of Higher Mathematics</a>
                <a class="mater" href="https://engblog.ru/mathematics-and-english">Говорим о математике на английском</a>
                <a class="mater" href="https://mksegment.ru/d/kak-izuchit-matematiku-na-anglijskom-yazyke">Как изучить математику на английском языке</a>
            </div>
            
    </div>
        
    </main>
</body>
<script>
    $(document).ready(function () {
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

        $(document).on('click', '#search-results li', function () {
            let path = $(this).data('path');
            if (path) {
                const urlParams = new URLSearchParams(path.split('?')[1]);

                const discipline = urlParams.get('discipline');
                const subsection = urlParams.get('subsection');
                const term = urlParams.get('term');

                window.location.href = `termin.php?discipline=${encodeURIComponent(discipline)}&subsection=${encodeURIComponent(subsection)}&term=${encodeURIComponent(term)}`;
            }
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.search-bar').length) {
                $('#search-results').hide();
            }
        });
    });
</script>

</html>
