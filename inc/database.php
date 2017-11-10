<?php

// Configuration de la base de données à placer dans un fichier différent pour la production
define('HOST', 'localhost'); // Domaine ou IP du serveur ou est située la base de données
define('USER', 'root'); // Nom d'utilisateur autorisé à se connecter à la base
define('PASS', ''); // Mot de passe de connexion à la base
define('DB', 'pokemon'); // Base de données sur laquelle on va faire les requêtes

$db_options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,// On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC   // Mode ASSOC par défaut pour les fetch
);

// Connexion renvoie soit un objet PDO, soit false
// Dans le cas ou le résultat est false, la raison est précisée dans le tableau $msg
function connexion(&$msg = array()) {
  global $db_options;

  // Variable de la connexion qui ne doit être généré qu'une seule fois
  static $db;
  // Si la connexion est déjà établie alors on retourne le même objet PDO
  if (isset($db))
    return $db;

  // Sinon connexion à la base de donnée et conservation du $db comme connexion
  $dsn = 'mysql:host=' . HOST . ';dbname=' . DB;
  try {
    $db = new PDO($dsn, USER, PASS, $db_options);
  } catch (PDOException $e) {
    $msg[] = "Erreur de connexion : " . $e->getMessage();
    return false;
  }
  if (!isset($db)) {
    $msg[] = "La connexion n'est pas instanciée";
    return false;
  }
  return $db;
}
