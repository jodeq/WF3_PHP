<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">

  <title>Formulaire d'insertion en base de donnée</title>

  <link rel="stylesheet" href="../css/style.css">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>

<?php
  require_once('../inc/function.php');

  $errors = [];
  $form_errors = [];

  // Configuration de la base de données à placer dans un fichier différent pour la production
  define('HOST', 'localhost'); // Domaine ou IP du serveur ou est située la base de données
  define('USER', 'root'); // Nom d'utilisateur autorisé à se connecter à la base
  define('PASS', ''); // Mot de passe de connexion à la base
  define('DB', 'pokemon'); // Base de données sur laquelle on va faire les requêtes

  $db_options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,// On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC   // Mode ASSOC par défaut pour les fetch
  );

  $dsn = 'mysql:host=' . HOST . ';dbname=' . DB;
  try {
    $db = new PDO($dsn, USER, PASS, $db_options);
  } catch (PDOException $e) {
    $errors[] = "Erreur de connexion : " . $e->getMessage();
  }

  if (formIsSubmit('insertPokedex')) {
    // code d'insertion
    $nom_proprietaire = $_POST['nom_proprietaire'];

    // Validation
    // Le nom ne doit pas être vide et faire au maximum 50 caractères
    //'' ou null
    if (empty($nom_proprietaire)) { // $nom_proprietaire != null && $nom_proprietaire != ''
      $form_errors['nom_proprietaire'] = "Le nom doit être renseigné";
    } elseif (strlen($nom_proprietaire) > 50) {
      $form_errors['nom_proprietaire'] = "Le nom doit faire 50 caractères maximum";
    } else {
      // ici nous ferons l'insertion
      echo "Insertion à faire";
    }
  }

  // Lister les pokedex enregistrés
  if (!$query = $db->query('SELECT * FROM pokedex')) {
    $errors[] = "Erreur lors de la création de la requête";
  }
  if(!$result = $query->fetch()) {
    $errors[] = "Aucune ligne trouvée";
  }

  if (count($errors) == 0) {
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
  }
?>

  <form method="post">
    <input type="hidden" name="insertPokedex" value="1"/>

    <label for="nom_proprietaire">Nom du proprietaire : </label>
    <input id="nom_proprietaire" name="nom_proprietaire" type="text" <?php echo isset($form_errors['nom_proprietaire']) ? 'class="error"' : '' ?> />
    <?php echo isset($form_errors['nom_proprietaire']) ? $form_errors['nom_proprietaire'] : ''?>
    <br>
    <button type="submit">Ajouter</button>
  </form>


<?php
  if (count($errors) > 0)
    echo "<p>" . implode("</p><p>", $errors) . "</p>";
  else
    echo "$table";
?>

  <!--script src="js/scripts.js"></script-->
</body>
</html>
