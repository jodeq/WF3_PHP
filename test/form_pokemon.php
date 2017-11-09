<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulaire d'insertion d'un pokemon</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</head>
<body>
  <?php
    require_once('../inc/function.php');

    // Liste des erreurs fonctionnelles (base de données, technique)
    $errors = [];

    // Liste des erreurs liées aux formulaires (mauvaises données, ...)
    $form_errors = [];

    // Configuration de la base de données à placer dans un fichier différent pour la production
    define('HOST', 'localhost'); // Domaine ou IP du serveur ou est située la base de données
    define('USER', 'root'); // Nom d'utilisateur autorisé à se connecter à la base
    define('PASS', ''); // Mot de passe de connexion à la base
    define('DB', 'pokemon'); // Base de données sur laquelle on va faire les requêtes

    $db_options = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,// On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC   // Mode ASSOC par défaut pour les fetch
    );

    // Connexion à la base de donnée
    $dsn = 'mysql:host=' . HOST . ';dbname=' . DB;
    try {
      $db = new PDO($dsn, USER, PASS, $db_options);
    } catch (PDOException $e) {
      $errors[] = "Erreur de connexion : " . $e->getMessage();
    }

    if (formIsSubmit('insertPokemon')) {
      // code d'insertion
      $numero_pokemon = $_POST['numero_pokemon'];
      $nom_pokemon = $_POST['nom_pokemon'];
      $experience_pokemon = $_POST['experience_pokemon'];
      $vie_pokemon = $_POST['vie_pokemon'];
      $defense_pokemon = $_POST['defense_pokemon'];
      $attaque_pokemon = $_POST['attaque_pokemon'];
      //$pokedex_pokemon = $_POST['pokedex_pokemon'];

      // Validation
      if (!filter_var($numero_pokemon, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
        $form_errors['numero_pokemon'] = "Le numéro doit être un nombre strictement supérieur à 0";
      }

      if (empty($nom_pokemon)) {
        $form_errors['nom_pokemon'] = "Le nom doit être renseigné";
      } elseif (strlen($nom_pokemon) > 50) {
        $form_errors['nom_pokemon'] = "Le nom doit faire 50 caractères maximum";
      }

      if (!filter_var($experience_pokemon, FILTER_VALIDATE_INT, array("options" => array("min_range" => 0)))) {
        $form_errors['experience_pokemon'] = "L'expérience doit être un nombre supérieur ou égal à 0";
      }

      if (!filter_var($vie_pokemon, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
        $form_errors['vie_pokemon'] = "La vie doit être un nombre strictement supérieur à 0";
      }

      if (!filter_var($defense_pokemon, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
        $form_errors['defense_pokemon'] = "La défense doit être un nombre strictement supérieur à 0";
      }

      if (!filter_var($attaque_pokemon, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
        $form_errors['attaque_pokemon'] = "L'attaque doit être un nombre strictement supérieur à 0";
      }

      /*if (empty($defense_pokemon)) {
        $form_errors['defense_pokemon'] = "La défence doit être renseignée";
      } elseif (!is_int($defense_pokemon)) {
        $form_errors['defense_pokemon'] = "La défense doit être un nombre";
      } elseif ($defense_pokemon <= 0) {
        $form_errors['defense_pokemon'] = "La défense doit être strictement supérieure à 0";
      }*/

      // S'il n'y a pas eu d'erreur ET que la connexion existe
      if (count($form_errors) == 0 && isset($db)) {
        $query = $db->prepare("
          INSERT INTO pokemon(numero,  nom,  experience,  vie,  defense,  attaque)
            VALUES           (:numero, :nom, :experience, :vie, :defense, :attaque)
        ");
        $query->bindParam(':numero', $numero_pokemon, PDO::PARAM_INT);
        $query->bindParam(':nom', $nom_pokemon, PDO::PARAM_STR);
        $query->bindParam(':experience', $experience_pokemon, PDO::PARAM_INT);
        $query->bindParam(':vie', $vie_pokemon, PDO::PARAM_INT);
        $query->bindParam(':defense', $defense_pokemon, PDO::PARAM_INT);
        $query->bindParam(':attaque', $attaque_pokemon, PDO::PARAM_INT);

        // exécution de la requête préparée
        try {
          $query->execute();
        } catch(PDOException $e) {
          // Il y a eu une erreur
          /*if ($e->getCode() == "23000")
            $form_errors['nom_proprietaire'] = "Le nom $nom_proprietaire existe déjà !";
          else {
            $form_errors['nom_proprietaire'] = "Erreur lors de l'insertion en base : " . $e->getMessage();
          }*/
          var_dump($e);
        }
      }
    }

    // Affichage des pokemons
    if (!$query = $db->query('SELECT * FROM pokemon')) {
      $errors[] = "Erreur lors de la création de la requête";
    }

    $table = "";

    while ($result = $query->fetch()) {
      // Première ligne : affichage des titres de colonnes
      if ($table == "") {
        $table = "
    <table class=\"table\">
      <thead>
        <tr>
          <th scope=\"col\">
          </th>
          <th scope=\"col\">
          " . implode('</th><th scope=\"col\">', array_keys($result)) . "
          </th>
        </tr>
      </thead>
      <tbody>
        ";
      }
      // Ajout d'une ligne dans la table
      $table .= "
        <tr>
          <td scope=\"row\">
            <a onclick=\"formSubmit('deletePokedex', 'id_delete', '" . $result['id'] . "');\"><i class=\"fa fa-trash-o fa-fw\" aria-hidden=\"true\"></i></a>
          </td>
          <td>
          " . implode('</td><td>', $result) . "
          </td>
        </tr>
      ";
    }

    if($table == "") {
      null;//$errors[] = "Aucune ligne trouvée";
    } else {
      $table .= "
      </tbody>
    </table>
      ";
    }
  ?>

  <div class="text-center">
    <img src="../img/pokemon.png" alt="" style="width: 30%;">
  </div>
  <div class="container">
    <div class="row align-items-center">
      <div class="col-sm-4 d-none d-sm-block">
        <img class="img-fluid mx-auto" src="../img/pokeball.png" alt="" />
      </div> <!-- Col -->
      <div class="col-xs-12 col-sm-8">
        <form method="post" id="insertPokemon">
          <input type="hidden" name="insertPokemon" value="1"/>
          <div class="form-control form-control-lg">
            <div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="numero_pokemon">Numéro Pokemon</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control <?php echo isset($form_errors['numero_pokemon']) ? 'is-invalid' : '' ?>" id="numero_pokemon" name="numero_pokemon" value="<?php echo getVal($_POST['numero_pokemon']) ?>">
                  <?php echo isset($form_errors['numero_pokemon']) ? '<div class="invalid-feedback">' . $form_errors['numero_pokemon'] . '</div>' : '' ?>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="nom_pokemon">Nom Pokemon :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control <?php echo isset($form_errors['nom_pokemon']) ? 'is-invalid' : '' ?>" id="nom_pokemon" name="nom_pokemon" value="<?php echo getVal($_POST['nom_pokemon']) ?>">
                  <?php echo isset($form_errors['nom_pokemon']) ? '<div class="invalid-feedback">' . $form_errors['nom_pokemon'] . '</div>' : '' ?>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="experience_pokemon">XP Pokemon :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control <?php echo isset($form_errors['experience_pokemon']) ? 'is-invalid' : '' ?>" id="experience_pokemon" name="experience_pokemon" value="<?php echo getVal($_POST['experience_pokemon']) ?>">
                  <?php echo isset($form_errors['experience_pokemon']) ? '<div class="invalid-feedback">' . $form_errors['experience_pokemon'] . '</div>' : '' ?>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="vie_pokemon">PV Pokemon :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control <?php echo isset($form_errors['vie_pokemon']) ? 'is-invalid' : '' ?>" id="vie_pokemon" name="vie_pokemon" value="<?php echo getVal($_POST['vie_pokemon']) ?>">
                  <?php echo isset($form_errors['vie_pokemon']) ? '<div class="invalid-feedback">' . $form_errors['vie_pokemon'] . '</div>' : '' ?>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="defense_pokemon">DEF Pokemon :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control <?php echo isset($form_errors['defense_pokemon']) ? 'is-invalid' : '' ?>" id="defense_pokemon" name="defense_pokemon" value="<?php echo getVal($_POST['defense_pokemon']) ?>">
                  <?php echo isset($form_errors['defense_pokemon']) ? '<div class="invalid-feedback">' . $form_errors['defense_pokemon'] . '</div>' : '' ?>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="attaque_pokemon">ATK Pokemon :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control <?php echo isset($form_errors['attaque_pokemon']) ? 'is-invalid' : '' ?>" id="attaque_pokemon" name="attaque_pokemon" value="<?php echo getVal($_POST['attaque_pokemon']) ?>">
                  <?php echo isset($form_errors['attaque_pokemon']) ? '<div class="invalid-feedback">' . $form_errors['attaque_pokemon'] . '</div>' : '' ?>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="pokedex_pokemon">N.Pokedex :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control <?php echo isset($form_errors['pokedex_pokemon']) ? 'is-invalid' : '' ?>" id="pokedex_pokemon" name="pokedex_pokemon" value="<?php echo getVal($_POST['pokedex_pokemon']) ?>">
                  <?php echo isset($form_errors['pokedex_pokemon']) ? '<div class="invalid-feedback">' . $form_errors['pokedex_pokemon'] . '</div>' : '' ?>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Valider</button>
              <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
          </div>
        </form>
      </div><!-- Col -->
    </div> <!-- Row -->

    <div class="text-center">
      <?php
        if (count($errors) > 0)
          echo "<p>" . implode("</p><p>", $errors) . "</p>";
        else
          echo "$table";
      ?>
    </div>
  </div> <!-- Container -->
</body>

</html>
