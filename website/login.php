<?php
header('Content-Type: application/json; charset=utf-8');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "users";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$connect) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка подключения к базе данных: ' . mysqli_connect_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim(strip_tags($_POST['login']));
    $password = trim(strip_tags($_POST['password']));

    if (empty($login) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, заполните все поля.']);
        exit;
    }

    $stmt = $connect->prepare("SELECT id, password FROM users WHERE login = ?");
    $stmt->bind_param("s", $login); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) { 
            echo json_encode(['status' => 'success', 'message' => 'Успешный вход']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Неверный пароль']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
    }

    $stmt->close();
}

mysqli_close($connect);
?>
