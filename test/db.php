<?php
/*
 * Script de test pour la connexion Base de donnée : BDD
 */
// Configuration de la base de données à placer dans un fichier différent pour la production
define('HOST', 'localhost'); // Domaine ou IP du serveur ou est située la base de données
define('USER', 'root'); // Nom d'utilisateur autorisé à se connecter à la base
define('PASS', ''); // Mot de passe de connexion à la base
define('DB', 'pokemon'); // Base de données sur laquelle on va faire les requêtes

$db_options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING // On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
);



echo "<pre>";
$dsn = 'mysql:host=' . HOST . ';dbname=' . DB;
try {
  $db = new PDO($dsn, USER, PASS, $db_options);
} catch (PDOException $e) {
  die("Erreur de connexion : " . $e->getMessage());
}

echo "A partir d'ici nous sommes connectés\n\n";

// Lister les pokedex enregistrés
$query = $db->query('SELECT * FROM pokedex');
$result = $query->fetch();
echo "result : ";
var_dump($result);

// Affichage d'un tableau PHP en tableau html
$table = "
  <table style='border-collapse: collapse;'>
    <thead>
      <tr>
        <th style='border: solid;'>
        " . implode('</th><th style="border: solid;">', array_keys($result)) . "
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style='border: solid;'>
        " . implode('</td><td style="border: solid;">', $result) . "
        </td>
      </tr>
    </tbody>
  </table>
";

echo "</pre>$table<pre>";

if (!$query = $db->query("INSERT INTO pokedex(nom_proprietaire) VALUES('Sacha')")) {
  echo 'Erreur de requête : ';
  var_dump($db->errorInfo());
  return 0;
}
$query->execute();

$query = $db->query('SELECT * FROM pokedex');
$result = $query->fetch();
echo "result : ";
var_dump($result);

echo "</pre>";


?>
