<?php
// Начало сессии для доступа к данным пользователя
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    // Перенаправление на страницу авторизации, если не авторизован
    header("Location: loginpage.html");
    exit;
}

require 'db_connect.php';

// Получаем данные пользователя по session['username']
$user = $_SESSION['username'];
$sql = "SELECT name, lastname, email FROM user WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

// Если пользователь найден
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstName = $row['name'];
    $lastName = $row['lastname'];
    $email = $row['email'];
} else {
    // Если не найден, можно перенаправить или вывести ошибку
    echo "Пользователь не найден";
    exit;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnglishMath</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand {
            font-size: 1.8rem;
            color: #fff !important;
            font-weight: 700;
        }
        .navbar-brand:hover {
            color: #eaeaea !important;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light">
        <a href="index.php" class="navbar-brand">EnglishMath</a>
    </nav>
    
    <div class="container">
        <h2>Личный кабинет</h2>
        <div class="form-group">
            <label for="firstName">Имя:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="lastName">Фамилия:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Поменять данные для входа</button>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Смена данных</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="change_credentials.php">
                                <div class="row mb-3">
                                    <label for="inputLogin" class="col-sm-2 col-form-label">Логин</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputLogin" name="new_login" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Пароль</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="inputPassword" name="new_password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">Посмотреть избранное</button>
            <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Избранное</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">   
                            <?php
                            // Выбираем избранные термины пользователя
                            $sql = "SELECT termin FROM favourites WHERE login = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $user);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<p>" . htmlspecialchars($row['termin']) . "</p>";
                                }
                            } else {
                                echo "<p>Нет избранных терминов.</p>";
                            }

                            $stmt->close();
                            $conn->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
