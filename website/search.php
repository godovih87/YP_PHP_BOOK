<?php
header('Content-Type: text/html; charset=utf-8');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "book";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim(strip_tags($_POST['book_name']));

    $query = "SELECT * FROM book WHERE name = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        echo "
            <div class='book-info'>
                <p><strong>Название:</strong> {$book['name']}</p>
                <p><strong>Автор:</strong> {$book['author']}</p>
                <p><strong>Жанр:</strong> {$book['genre']}</p>
                <p><strong>Дата:</strong> {$book['date']}</p>
                <p><strong>Издательство:</strong> {$book['publishing house']}</p>
            </div>
        ";
    } else {
        echo "<p class='error'>Книга не найдена</p>";
    }
}
?>
