<?php
// Entête HTML ce require permet de charger toutes les balises d'en-tête de la page HTML
require('header.php');

// Gestion de la base de donnée : paramètres et fonctions de bases
require('../inc/database.php');

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
  SELECT numero, nom, experience, vie, defense, attaque, nom_proprietaire
    FROM pokemon
      LEFT JOIN pokedex on (id_pokedex = pokedex.id)
    WHERE pokemon.id=:id
");
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();

if (!$result = $query->fetch())
  die("Pokémon id $id inconnu !");

$title = ($mode_edit ? "Modification" : "Consultation") . " du pokemon $id";
$image = "../img/pokeball.png";

?>

<div class="container">
  <h1 class="text-center"><?php echo $title ?></h1>
  <div class="row align-items-center">
    <div class="col-sm-4 d-none d-sm-block">
      <img class="img-fluid mx-auto" src="<?php echo $image; ?>" alt="" />
    </div> <!-- Col -->
    <div class="col-xs-12 col-sm-8">
      <form method="post" id="insertPokemon">
        <input type="hidden" name="insertPokemon" value="1"/>
        <div class="form-control">
          <div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="numero">Numéro</label>
              <div class="col-sm-10">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['numero']) ? 'is-invalid' : '' ?>"
                  id="numero"
                  name="numero"
                  value="<?php echo isset($result['numero']) ? $result['numero'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['numero']) ? '<div class="invalid-feedback">' . $form_errors['numero'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="nom">Nom</label>
              <div class="col-sm-10">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['nom']) ? 'is-invalid' : '' ?>"
                  id="nom"
                  name="nom"
                  value="<?php echo isset($result['nom']) ? $result['nom'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['nom']) ? '<div class="invalid-feedback">' . $form_errors['nom'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="experience">Expérience</label>
              <div class="col-sm-10">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['experience']) ? 'is-invalid' : '' ?>"
                  id="experience"
                  name="experience"
                  value="<?php echo isset($result['experience']) ? $result['experience'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['experience']) ? '<div class="invalid-feedback">' . $form_errors['experience'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="vie">Vie</label>
              <div class="col-sm-10">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['vie']) ? 'is-invalid' : '' ?>"
                  id="vie"
                  name="vie"
                  value="<?php echo isset($result['vie']) ? $result['vie'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['vie']) ? '<div class="invalid-feedback">' . $form_errors['vie'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="defense">Défense</label>
              <div class="col-sm-10">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['defense']) ? 'is-invalid' : '' ?>"
                  id="defense"
                  name="defense"
                  value="<?php echo isset($result['defense']) ? $result['defense'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['defense']) ? '<div class="invalid-feedback">' . $form_errors['defense'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="attaque">Attaque</label>
              <div class="col-sm-10">
                <input
                  type="text"
                  class="form-control <?php echo isset($form_errors['attaque']) ? 'is-invalid' : '' ?>"
                  id="attaque"
                  name="attaque"
                  value="<?php echo isset($result['attaque']) ? $result['attaque'] : '' ?>"
                  <?php echo $mode_edit ? '' : 'readonly' ?>
                >
                <?php echo isset($form_errors['attaque']) ? '<div class="invalid-feedback">' . $form_errors['attaque'] . '</div>' : '' ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="pokedex">Propriétaire</label>
              <div class="col-sm-10">
                <?php if ($mode_edit) : ?>
                <select
                  class="form-control <?php echo isset($form_errors['pokedex']) ? 'is-invalid' : '' ?>"
                  id="pokedex"
                  name="pokedex"
                  value="<?php echo isset($result['pokedex']) ? $result['pokedex'] : '' ?>"
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
                  value="<?php echo isset($result['nom_proprietaire']) ? $result['nom_proprietaire'] : '' ?>"
                  readonly
                >
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary">Valider</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </div>
        </div>
      </form>
    </div><!-- col -->
  </div> <!-- row -->
</div> <!-- container -->

<?php

// Fin du HTML
require('footer.php');
