<?php

$id = $_GET['id'];

require('../inc/database.php');
require('../inc/function.php');
$db = connexion($msg);


$str_query = "SELECT * FROM pokemon WHERE id = :id";
echo $str_query;

$query = $db->prepare($str_query);
$query->bindValue(':id', $id, PDO::PARAM_INT);
$result = $query->fetchAll();
echo "<pre>";
var_dump($result);
echo "</pre>";


if (formIsSubmit('test_xss')) {
  $pseudo = $_POST['pseudo'];

  $query = $db->prepare('INSERT INTO test_drop(col1) VALUES (:pseudo)');
  $query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
  $query->execute();

  echo "query : INSERT INTO test_drop(col1) VALUES ($pseudo)";

}


$query = $db->query("select * from test_drop");
$result = $query->fetchAll();

foreach($result as $res) {
  echo "<p>" . htmlspecialchars($res['col1']) . "</p>";
}

?>
<form method="POST">
    <input type="hidden" name="test_xss" value="1"/>
    <input name="pseudo" type="text" />
    <input type="submit" value="OK" />
</form>
