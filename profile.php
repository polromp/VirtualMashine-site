<?php
session_start();

$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : null;

if (!isset($_SESSION['username'])) {
    header("Location: loginpage.html");
    exit;
}

require 'db_connect.php';

$user = $_SESSION['username'];
$sql = "SELECT name, lastname, email FROM user WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstName = $row['name'];
    $lastName = $row['lastname'];
    $email = $row['email'];
} else {
    echo "Пользователь не найден";
    exit;
}
$stmt->close();

if (isset($_GET['remove_favourite'])) {
    $termin_to_remove = $_GET['remove_favourite'];
    $remove_sql = "DELETE FROM favourites WHERE login = ? AND termin = ?";
    $stmt_remove = $conn->prepare($remove_sql);
    $stmt_remove->bind_param("ss", $user, $termin_to_remove);
    $stmt_remove->execute();
    $stmt_remove->close();
    header("Location: profile.php");
    exit;
}

if (isset($_GET['add_favourite'])) {
    $termin_to_add = $_GET['add_favourite'];
    $add_sql = "INSERT INTO favourites (login, termin) VALUES (?, ?)";
    $stmt_add = $conn->prepare($add_sql);
    $stmt_add->bind_param("ss", $user, $termin_to_add);
    $stmt_add->execute();
    $stmt_add->close();
    header("Location: profile.php");
    exit;
}
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
        body{
            background: radial-gradient(circle, #B6D0E2, #7FB3D5, #549DC7);
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
        .trash-icon {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-left: 10px;
            float: right;
            margin-right: 10px;
        }
        
        p {
            width: auto;
            height: 50px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s;
            align-content: center;
        }
        #textarea {
            width: 100%;
            height: 200px;
        }
        .tema{
            margin-bottom: 10px;
            border: 1px solid #d3d3d3;
            border-radius: 10px;
            width: 100%;
            outline:none;
        }
        header {
            background-color: #549DC7; 
            padding: 10px 20px;
            align-items: center;
        }
        .button1{
            width: 100%;
            margin-bottom: 10px;
        }
        .logo{
            color: black;
            text-decoration: none;
        }
        .logo:hover{
            text-decoration: underline;
        }
        header h1{
            margin: 0;
            font-size: 1.8rem;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }

    </style>
</head>
<body >
    
    <header>
        <a class="logo" href="index.php"><h1>EnglishMath</h1></a>
    </header>
    
    <div class="container position-absolute top-50 start-50 translate-middle" >
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
            <div class="izbr">
                <button type="button" class="btn btn-primary button1" data-bs-toggle="modal" data-bs-target="#exampleModal2">Посмотреть избранное</button>
            </div>
            
            <div class="faq">
                <button type="button" class="btn btn-primary button1" data-bs-toggle="modal" data-bs-target="#exampleModal3">Обратная связь</button>
            </div>
            <div class="form-group">
                <a href="logout.php" class="btn btn-danger button1">Выйти</a>
            </div>
            
            
        </div>
    </div>
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Избранное</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php
                            $sql = "SELECT termin, discipline, subsection FROM favourites WHERE login = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $user);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $termin = htmlspecialchars($row['termin']); 
                                    $discipline = htmlspecialchars($row['discipline']); 
                                    $subsection = htmlspecialchars($row['subsection']); 
                                    
                                    $file_path = "terms/{$discipline}/{$subsection}/{$termin}.txt";
                                    
                                    echo "<p><a href='termin.php?discipline=" . urlencode($discipline) . "&subsection=" . urlencode($subsection) . "&term=" . urlencode($termin) . "'>" . $termin . "</a>";
                                    echo " <a href='?remove_favourite=" . urlencode($termin) . "'><img src='photos/trash.png' class='trash-icon' alt='Удалить'></a></p>";
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

            <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Обратная связь</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Расскажите о проблеме</p>
                            <form method="POST" action="save_feedback.php">
                                <input name="tema" type="text" class="tema" placeholder="Тема">
                                <textarea name="feedback" id="textarea" class="form-control"></textarea>
                                <button type="submit" class="btn btn-primary mt-3">Отправить</button>
                            </form>
                        </div>
                    </div> 
                </div>
            </div>
</body>
</html>
