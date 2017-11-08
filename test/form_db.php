<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">

  <title>Formulaire d'insertion en base de donnée</title>

  <!--link rel="stylesheet" href="css/styles.css?v=1.0"-->

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>
  <form>
    <label for="nom_proprietaire">Nom du proprietaire : </label>
    <input id="nom_proprietaire" type="text"/>

    <button type="submit">Ajouter</button>
  </form>

<?php
  // Configuration de la base de données à placer dans un fichier différent pour la production
  define('HOST', 'localhost'); // Domaine ou IP du serveur ou est située la base de données
  define('USER', 'root'); // Nom d'utilisateur autorisé à se connecter à la base
  define('PASS', ''); // Mot de passe de connexion à la base
  define('DB', 'pokemon'); // Base de données sur laquelle on va faire les requêtes

  $db_options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,// On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC   // Mode ASSOC par défaut pour les fetch
  );

  echo "<pre>";
  $dsn = 'mysql:host=' . HOST . ';dbname=' . DB;
  try {
    $db = new PDO($dsn, USER, PASS, $db_options);
  } catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
  }

  // Lister les pokedex enregistrés
  if (!$query = $db->query('SELECT * FROM pokedex'))
    die("Erreur lors de la création de la requête");
  if(!$result = $query->fetch())
    die("Aucune ligne trouvée");

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

  echo "$table";
?>

  <!--script src="js/scripts.js"></script-->
</body>
</html>
