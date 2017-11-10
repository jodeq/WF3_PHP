<?php
// Entête HTML ce require permet de charger toutes les balises d'en-tête de la page HTML
require('header.php');

// Gestion de la base de donnée : paramètres et fonctions de bases
require('../inc/database.php');
require('../inc/function.php');

$errors = [];
$form_errors = [];

// Connexion à la base
if (!$db = connexion($msg))
  echo "Erreur : " . implode($msg);

if (!isset($_GET['id']))
  die("Veuillez préciser un id de pokémon !");
$id = $_GET['id'];

$mode_edit = (isset($_GET['edit']) ? $_GET['edit'] : 0) == 1;

$query = $db->prepare("
  SELECT numero, nom, experience, vie, defense, attaque, nom_proprietaire, id_pokedex
    FROM pokemon
      LEFT JOIN pokedex on (id_pokedex = pokedex.id)
    WHERE pokemon.id=:id
");
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();

if (!$pokemon = $query->fetch())
  die("Pokémon id $id inconnu !");


if (formIsSubmit('updatePokemon')) {
  // code d'insertion
  $numero = $_POST['numero'];
  $nom = $_POST['nom'];
  $experience = $_POST['experience'];
  $vie = $_POST['vie'];
  $defense = $_POST['defense'];
  $attaque = $_POST['attaque'];
  $pokedex = $_POST['pokedex'];

  // Validation
  if (!filter_var($numero, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
    $form_errors['numero'] = "Le numéro doit être un nombre strictement supérieur à 0";
  }

  if (empty($nom)) {
    $form_errors['nom'] = "Le nom doit être renseigné";
  } elseif (strlen($nom) > 50) {
    $form_errors['nom'] = "Le nom doit faire 50 caractères maximum";
  }

  if (!filter_var($experience, FILTER_VALIDATE_INT, array("options" => array("min_range" => 0)))) {
    $form_errors['experience'] = "L'expérience doit être un nombre supérieur ou égal à 0";
  }

  if (!filter_var($vie, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
    $form_errors['vie'] = "La vie doit être un nombre strictement supérieur à 0";
  }

  if (!filter_var($defense, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
    $form_errors['defense'] = "La défense doit être un nombre strictement supérieur à 0";
  }

  if (!filter_var($attaque, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
    $form_errors['attaque'] = "L'attaque doit être un nombre strictement supérieur à 0";
  }

  if (!filter_var($pokedex, FILTER_VALIDATE_INT, array("options" => array("min_range" => 0)))) {
    $form_errors['pokedex'] = "La valeur du pokedex n'est pas valide";
  }

  // S'il n'y a pas eu d'erreur ET que la connexion existe
  if (count($form_errors) == 0 && isset($db)) {
    $query = $db->prepare("
      UPDATE pokemon
        SET numero = :numero,
            nom = :nom,
            experience = :experience,
            vie = :vie,
            defense = :defense,
            attaque = :attaque,
            id_pokedex = :id_pokedex
        WHERE id = :id
    ");
    $query->bindParam(':numero', $numero, PDO::PARAM_INT);
    $query->bindParam(':nom', $nom, PDO::PARAM_STR);
    $query->bindParam(':experience', $experience, PDO::PARAM_INT);
    $query->bindParam(':vie', $vie, PDO::PARAM_INT);
    $query->bindParam(':defense', $defense, PDO::PARAM_INT);
    $query->bindParam(':attaque', $attaque, PDO::PARAM_INT);
    $query->bindParam(':id_pokedex', $pokedex, PDO::PARAM_INT);
    $query->bindParam(':id', $id, PDO::PARAM_INT);

    // exécution de la requête préparée
    try {
      $query->execute();
    } catch(PDOException $e) {
      // Il y a eu une erreur
      var_dump($e);
    }

    // Rafraichissement du pokemon
    $query = $db->prepare("
      SELECT numero, nom, experience, vie, defense, attaque, nom_proprietaire, id_pokedex
        FROM pokemon
          LEFT JOIN pokedex on (id_pokedex = pokedex.id)
        WHERE pokemon.id=:id
    ");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    if (!$pokemon = $query->fetch())
      die("Pokémon id $id inconnu !");

    showMessage("Mise à jour faite");
  }
}

// Liste des pokedex
$pokedexs = [];
if ($mode_edit) {
  $pokedex_options = "";
  if (!$query = $db->query('SELECT id, nom_proprietaire FROM pokedex')) {
    $errors[] = "Erreur lors de la création de la requête";
  } else {
    $pokedexs = $query->fetchAll();

    foreach($pokedexs as $pokedex) {
      $pokedex_options .= '<option value="' . $pokedex['id'] . '" ' . ($pokedex['id'] == $pokemon['id_pokedex'] ? 'selected' : '') . '>' . $pokedex['nom_proprietaire'] . '</option>';
    }
  }
}

$title = ($mode_edit ? "Modification" : "Consultation") . " du pokemon $id";
$image = "../img/pokeball.png";

?>

<div class="container">
  <h1 class="text-center"><?php echo $title ?></h1>
  <div class="row align-items-center">
    <div class="col-sm-4 d-none d-sm-block">
      <img class="img-fluid mx-auto" src="<?php echo $image; ?>" alt="" />
    </div> <!-- col -->
    <div class="col-xs-12 col-sm-8">
      <form method="post" id="updatePokemon" enctype="multipart/form-data">
        <input type="hidden" name="updatePokemon" value="1"/>
        <div class="form-control">
          <div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="numero">Numéro</label>
              <div class="col-sm-9">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['numero']) ? 'is-invalid' : '' ?>"
                  id="numero"
                  name="numero"
                  value="<?php echo isset($pokemon['numero']) ? $pokemon['numero'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['numero']) ? '<div class="invalid-feedback">' . $form_errors['numero'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="nom">Nom</label>
              <div class="col-sm-9">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['nom']) ? 'is-invalid' : '' ?>"
                  id="nom"
                  name="nom"
                  value="<?php echo isset($pokemon['nom']) ? $pokemon['nom'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['nom']) ? '<div class="invalid-feedback">' . $form_errors['nom'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="experience">Expérience</label>
              <div class="col-sm-9">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['experience']) ? 'is-invalid' : '' ?>"
                  id="experience"
                  name="experience"
                  value="<?php echo isset($pokemon['experience']) ? $pokemon['experience'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['experience']) ? '<div class="invalid-feedback">' . $form_errors['experience'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="vie">Vie</label>
              <div class="col-sm-9">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['vie']) ? 'is-invalid' : '' ?>"
                  id="vie"
                  name="vie"
                  value="<?php echo isset($pokemon['vie']) ? $pokemon['vie'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['vie']) ? '<div class="invalid-feedback">' . $form_errors['vie'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="defense">Défense</label>
              <div class="col-sm-9">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['defense']) ? 'is-invalid' : '' ?>"
                  id="defense"
                  name="defense"
                  value="<?php echo isset($pokemon['defense']) ? $pokemon['defense'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['defense']) ? '<div class="invalid-feedback">' . $form_errors['defense'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="attaque">Attaque</label>
              <div class="col-sm-9">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['attaque']) ? 'is-invalid' : '' ?>"
                  id="attaque"
                  name="attaque"
                  value="<?php echo isset($pokemon['attaque']) ? $pokemon['attaque'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['attaque']) ? '<div class="invalid-feedback">' . $form_errors['attaque'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="pokedex">Propriétaire</label>
              <div class="col-sm-9">
                <?php if ($mode_edit) : ?>
                <select
                  class="form-control <?php echo isset($form_errors['pokedex']) ? 'is-invalid' : '' ?>"
                  id="pokedex"
                  name="pokedex"
                  value="<?php echo isset($pokemon['pokedex']) ? $pokemon['pokedex'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                  <option value="">- Aucun -</option>
                  <?php echo $pokedex_options; ?>
                </select>
                <?php echo isset($form_errors['pokedex']) ? '<div class="invalid-feedback">' . $form_errors['pokedex'] . '</div>' : '' ?>
                <?php else : ?>
                <input
                  type="text"
                  class="form-control"
                  id="attaque"
                  name="attaque"
                  value="<?php echo isset($pokemon['nom_proprietaire']) ? $pokemon['nom_proprietaire'] : '' ?>"
                  readonly
                >
                <?php endif; ?>
              </div>
            </div>
            <?php if ($mode_edit) : ?>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for="image">Nouvelle image</label>
              <div class="col-sm-9">
              <input type="file" id="image" name="image" accept="image/*"/>
            </div>
            <?php endif; ?>
          </div>
          <?php if ($mode_edit) : ?>
          <div class="text-center">
            <button type="submit" class="btn btn-primary">Valider</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </div>
          <?php endif; ?>
        </div>
      </form>
    </div><!-- col -->
  </div> <!-- row -->
</div> <!-- container -->

<?php

// Fin du HTML
require('footer.php');
