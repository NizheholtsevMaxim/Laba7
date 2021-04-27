<?php
// Данные из формы запроса
$name = $_POST['name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$author = $_POST['author'];
?>

<h2>Разультаты поиска (<?php print ($name == 'all' ? '' : $name) . ' ' . ($author == 'all' ? '' : $author) . ' ' . $start_date . '-' . $end_date; ?>)</h2>

<?php
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
  ?>

  <table>
    <thead>
      <tr>
        <td>Название</td>
        <td>ISBN</td>
        <td>Издание</td>
        <td>Год выхода</td>
        <td>Кол-во страниц</td>
        <td>Автор</td>
        <td>Тип издания</td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($stmt as $row) { ?>
        <tr>
          <td><?php print($row[0]); ?></td>
          <td><?php print($row['ISBN']); ?></td>
          <td><?php print($row['publisher']); ?></td>
          <td><?php print($row['year']); ?></td>
          <td><?php print($row['quantity']); ?></td>
          <td><?php print($row['name']); ?></td>
          <td><?php print($row['literature']); ?></td>
        </tr>

      <?php } ?>

    </tbody>
  </table>

  <?php
  $stmt = null;
  $pdo = null;
} catch (PDOException $e) {
  //выводим ошибку
  print "Error!: " . $e->getMessage() . "<br/>";
}
?>

