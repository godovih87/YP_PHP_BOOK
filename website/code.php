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
    $surname = trim(strip_tags($_POST['surname']));
    $name = trim(strip_tags($_POST['name']));
    $date_birthday = trim(strip_tags($_POST['birthday']));
    $mail = trim(strip_tags($_POST['mail']));
    $login = trim(strip_tags($_POST['login']));
    $password = trim(strip_tags($_POST['password']));

    if (empty($surname) || empty($name) || empty($date_birthday) || empty($mail) || empty($login) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, заполните все поля.']);
        exit;
    }
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Некорректный формат email.']);
        exit;
    }

    $check_login_sql = "SELECT id FROM users WHERE login = '$login'";
    $result = mysqli_query($connect, $check_login_sql);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Этот логин уже занят. Пожалуйста, выберите другой.']);
        exit;
    }
    $sql = "INSERT INTO users (surname, name, birthday, mail, login, password)
            VALUES ('$surname', '$name', '$date_birthday', '$mail', '$login', '$password')";

    if (mysqli_query($connect, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Данные успешно внесены в систему!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка при сохранении данных в базе: ' . mysqli_error($connect)]);
    }
}

mysqli_close($connect);
?>
