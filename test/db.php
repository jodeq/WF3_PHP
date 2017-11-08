<?php
/*
 * Script de test pour la connexion Base de donnée : BDD
 */
echo "<pre>";
$db = new PDO('mysql:host=localhost;dbname=pokemon', 'root', '');
echo "DB : ";
var_dump($db);

$query = $db->query('SELECT * FROM pokedex');
$result = $query->fetch();
echo "result : ";
var_dump($result);

$query = $db->query("INSERT INTO pokedex(nom_proprietaire) VALUES('Sacha')");
$query->execute();

$query = $db->query('SELECT * FROM pokedex');
$result = $query->fetch();
echo "result : ";
var_dump($result);

echo "</pre>";


?>
