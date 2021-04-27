<?php

// Данные из формы запроса
$name = $_POST['name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$author = $_POST['author'];

$db_name = 'maxim';
$user = 'root';
$pass = '';

$pdo = new PDO('mysql:host=localhost;dbname=' . $db_name, $user, $pass);
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


$dom = new DOMDocument();

$dom->encoding = 'utf-8';

$dom->xmlVersion = '1.0';

$dom->formatOutput = true;

$xml_file_name = 'response.xml';

$root = $dom->createElement('Literatures');
foreach ($stmt as $row) { 
$book_node = $dom->createElement('book');

$attr_book_id = new DOMAttr('ID_Book', $row['FID_Book']);
$book_node->setAttributeNode($attr_book_id);

$child_node_name = $dom->createElement('name', $row[0]);
$book_node->appendChild($child_node_name);

$child_node_year = $dom->createElement('year', $row['year']);
$book_node->appendChild($child_node_year);

$child_node_isbn = $dom->createElement('ISBN', $row['ISBN']);
$book_node->appendChild($child_node_isbn);

$child_node_quantity = $dom->createElement('quantity', $row['quantity']);
$book_node->appendChild($child_node_quantity);

$child_node_publisher = $dom->createElement('publisher', $row['publisher']);
$book_node->appendChild($child_node_publisher);

$child_node_literature = $dom->createElement('literature', $row['literature']);
$book_node->appendChild($child_node_literature);

$child_node_author = $dom->createElement('author', $row['name']);
$book_node->appendChild($child_node_author);

$root->appendChild($book_node);

$dom->appendChild($root);
}
$dom->save($xml_file_name);

?>