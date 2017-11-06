<html>
<head>
  <!-- Insérer le css ici -->
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php
// Initialisation des variables

// Mes pokemons
$pokemons = array();

// Pikachu
$pikachu = [
  'pv' => isset($_GET['pv_pokemon1']) ? $_GET['pv_pokemon1'] : 25, // 25 Points de vie par défaut
  'attaque' => isset($_GET['attaque_pokemon1']) ? $_GET['attaque_pokemon1'] : 15,
  'defense' => isset($_GET['defense_pokemon1']) ? $_GET['defense_pokemon1'] : 10
];
$pokemons['Pikachu'] = $pikachu;

// Bulbizarre
$bulbizarre = [
  'pv' => isset($_GET['pv_pokemon2']) ? $_GET['pv_pokemon2'] : 30,
  'attaque' => isset($_GET['attaque_pokemon2']) ? $_GET['attaque_pokemon2'] : 8,
  'defense' => isset($_GET['defense_pokemon2']) ? $_GET['defense_pokemon2'] : 20
];
$pokemons['Bulbizarre'] = $bulbizarre;

// tableau de validation
$form_error = [];

// Validation du formulaire
foreach($_GET as $input => $value) {
  if ($input === 'pokemon1' || $input === 'pokemon2') {
    if (!isset($pokemons[$value])) {
      echo '<p style="">Le pokemon ' . $value . ' n\'est pas un pokemon disponible</p>';
      $form_error[$input] = 1;
    }
  } elseif (empty($value) || !ctype_digit($value) || $value <= 0) {
    echo '<p style="">Le champ ' . $input . ' doit un entier strictement supérieur à 0</p>';
    $form_error[$input] = 1;
  }
}

?>

  <form>
    <fieldset>
      <legend>Pokemon 1 :
        <select name="pokemon1" <?php echo isset($form_error['pokemon1']) ? 'class="error"' : ''; ?>>
          <?php
            foreach($pokemons as $pokemon => $stats) {
              echo '<option value="' . $pokemon . '">' . $pokemon . '</option>';
            }
          ?>
        </select>
      </legend>
      <div>Points de vie : <input type="test" name="pv_pokemon1" value="<?php echo $pikachu['pv']; ?>" <?php echo isset($form_error['pv_pokemon1']) ? 'class="error"' : ''; ?> /></div>
      <div>Points de défense : <input type="test" name="defense_pokemon1" value="<?php echo $pikachu['defense']; ?>" <?php echo isset($form_error['defense_pokemon1']) ? 'class="error"' : ''; ?> /></div>
      <div>Points d'attaque : <input type="test" name="attaque_pokemon1" value="<?php echo $pikachu['attaque']; ?>" <?php echo isset($form_error['attaque_pokemon1']) ? 'class="error"' : ''; ?> /></div>
    </fieldset>
    <fieldset>
      <legend>Pokemon 2 :
        <select name="pokemon2" <?php echo isset($form_error['pokemon2']) ? 'class="error"' : ''; ?>>
          <?php
            foreach($pokemons as $pokemon => $stats) {
              echo '<option value="' . $pokemon . '">' . $pokemon . '</option>';
            }
          ?>
        </select>
      </legend>
      <div>Points de vie : <input type="test" name="pv_pokemon2" value="<?php echo $bulbizarre['pv']; ?>" <?php echo isset($form_error['pv_pokemon2']) ? 'class="error"' : ''; ?> /></div>
      <div>Points de défense : <input type="test" name="defense_pokemon2" value="<?php echo $bulbizarre['defense']; ?>" <?php echo isset($form_error['defense_pokemon2']) ? 'class="error"' : ''; ?> /></div>
      <div>Points d'attaque : <input type="test" name="attaque_pokemon2" value="<?php echo $bulbizarre['attaque']; ?>" <?php echo isset($form_error['attaque_pokemon2']) ? 'class="error"' : ''; ?> /></div>
    </fieldset>
    <button type="submit">Combattez !</button>
  </form>

<?php
/**
 * Bienvenue dans ce module PHP
 * Nous allons travailler à la réalisation d'un pokedex
 */

// Vérifions les informations
/*echo "<pre>";
var_dump($_GET);
var_dump($_POST);
echo "</pre>";*/
if (count($form_error) > 0)
  die ("Le combat est reporté pour cause d'erreurs de saisie");


$tour = 0;

//echo "Date : " . date('d/m/Y : H:i:s');

// Boucle de combat
do {
  echo "<h2> Tour : " . ++$tour . " à " . date('H:i:s') . "</h2>";

  // pikachu attaque bulbizarre
  echo "<h3>Pikachu attaque bulbizarre</h3>";
  if ($pikachu['attaque'] >= $bulbizarre['defense']) {
    // L'attaque est supérieure à la défense : pikachu touche
    $coup = $pikachu['attaque'] - $bulbizarre['defense'] + 1; // La valeur du coup est la différence entre l'attaque et la défense
    $bulbizarre['pv'] -= $coup;
    echo "<p>Bulbizarre perd $coup PV, il lui reste " . $bulbizarre['pv'] . " PV</p>";
  } else {
    // La défense est supérieure à l'attaque, pikachu prend la moitié du coup et la défense baisse un peu
    $coup = ($bulbizarre['defense'] - $pikachu['attaque']) / 2;
    $pikachu['pv'] -= $coup;
    $bulbizarre['defense'] -= 1;
    echo "<p>Bulbizarre perd 1 Points de défense, il lui reste " . $bulbizarre['defense'] . " Points de défense</p>";
    echo "<p>Pikachu râte son attaque ! Il perd $coup Points de vie, il lui reste " . $pikachu['pv'] . " Points de vie</p>";
  }

  if ($bulbizarre['pv'] <= 0) // S'il n'y a pas d'accolades après un if, seule la première instruction est filtrée par le if
    echo "<p>Bulbizarre est KO !</p>";
  if ($pikachu['pv'] <= 0)
    echo "<p>Pikachu est KO !</p>";

  // Et maintenant la contre-attaque : à vous de jouer !
  // bulbizarre attaque pikachu
  echo "<h3>Bulbizarre attaque Pikachu</h3>";
  if ($bulbizarre['attaque'] >= $pikachu['defense']) {
    // L'attaque est supérieure à la défense : bulbizarre touche
    $coup = $bulbizarre['attaque'] - $pikachu['defense'] + 1; // La valeur du coup est la différence entre l'attaque et la défense
    $pikachu['pv'] -= $coup;
    echo "<p>Pikachu perd $coup PV, il lui reste " . $pikachu['pv'] . " PV</p>";
  } else {
    // La défense est supérieure à l'attaque, bulbizarre prend la moitié du coup et la défense baisse un peu
    $coup = ($pikachu['defense'] - $bulbizarre['attaque']) / 2;
    $bulbizarre['pv'] -= $coup;
    $pikachu['defense'] -= 1;
    echo "<p>Pikachu perd 1 Points de défense, il lui reste " . $pikachu['defense'] . " Points de défense</p>";
    echo "<p>Bulbizarre râte son attaque ! Il perd $coup Points de vie, il lui reste " . $bulbizarre['pv'] . " Points de vie</p>";
  }

  if ($bulbizarre['pv'] <= 0) // S'il n'y a pas d'accolades après un if, seule la première instruction est filtrée par le if
    echo "<p>Bulbizarre est KO !</p>";
  if ($pikachu['pv'] <= 0)
    echo "<p>Pikachu est KO !</p>";

} while ($pikachu['pv'] > 0 && $bulbizarre['pv'] > 0); // === !($pikachu['pv'] <= 0 || $bulbizarre['pv'] <= 0)



// Ajoutons quelques baies pour restaurer des Points de Vies
$pv_baie_rouge = 50;
$pv_baie_noire = 30;

// Bulbizarre mange une baie rouge
// Pikachu mange une baie noire

?>


</body>
</html>
