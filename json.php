<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

// Данные из формы запроса
$name = $_POST['name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$author = $_POST['author'];

//Данные бызы данных для соединения
$db_name = 'maxim';
$user = 'root';
$pass = '';

try {
  //Соединение с базой данных
  $pdo = new PDO('mysql:host=localhost;dbname=' . $db_name, $user, $pass);

  //выбираем таблицу из базы данных
  $stmt = $pdo->prepare("
                  SELECT
                    l.name,
                    l.year,
                    l.ISBN,
                    l.literature,
                    l.quantity,
                    l.publisher,
                    b.FID_Book,
                    b.FID_Author,
                    a.ID_Authors,
                    a.name
                  FROM
                      book_authors b
                  INNER JOIN literarure l
                    ON b.FID_Book = l.ID_Book
                  INNER JOIN authors a
                    ON b.FID_Author = a.ID_Authors
                  WHERE
                    l.name LIKE :name
                   AND
                    a.name LIKE :author
                   AND
                   l.year BETWEEN :start_date AND :end_date;
");


  $stmt->execute(array(
      'name' => $name == 'all' ? '%' : $name,
      'author' => $author == 'all' ? '%' : $author,
      'start_date' => $start_date,
      'end_date' => $end_date
          )
  );
  $response = array();
  foreach ($stmt as $row) {
    $response[] = array(
        'name' => $row[0],
        'ISBN' => $row['ISBN'],
        'publisher' => $row['publisher'],
        'year' => $row['year'],
        'quantity' => $row['quantity'],
        'author' => $row['name'],
        'literature' => $row['literature']
    );
  }

  echo json_encode($response);

  $stmt = null;
  $pdo = null;
} catch (PDOException $e) {
  //выводим ошибку
  print "Error!: " . $e->getMessage() . "<br/>";
}
?>