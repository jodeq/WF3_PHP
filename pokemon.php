<html>
<head>
  <!-- Insérer le css ici -->
  <link rel="stylesheet" type="text/css" href="style.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>

<?php
// Initialisation des variables

// Mes pokemons
$pokemons = array();

// Les pokemons ont 50 points à répartir entre vie, défense et attaque
// Pikachu
$pikachu = [
  'pv' => 25,
  'attaque' => 15,
  'defense' => 10
];
$pokemons['Pikachu'] = $pikachu;

// Bulbizarre
$bulbizarre = [
  'pv' => 30,
  'attaque' => 8,
  'defense' => 12
];
$pokemons['Bulbizarre'] = $bulbizarre;

// Salameche
$salameche = [
  'pv' => 15,
  'attaque' => 20,
  'defense' => 15
];
$pokemons['Salameche'] = $salameche;



echo '
  <script type="text/javascript">
    var pokemons = [];
';
foreach($pokemons as $pokemon => $stats) {
  echo 'pokemons["' . $pokemon . '"] = [];' . "\n";
  foreach ($stats as $cle => $valeur) {
    echo 'pokemons["' . $pokemon . '"]["' . $cle . '"] = ' . $valeur . "\n";
  }
}
echo '

  function changePokemon1(event) {
    selPokemon = $(this).val();
    $("[name=\'pv_pokemon1\']").val(pokemons[selPokemon]["pv"]);
    $("[name=\'defense_pokemon1\']").val(pokemons[selPokemon]["defense"]);
    $("[name=\'attaque_pokemon1\']").val(pokemons[selPokemon]["attaque"]);
  }

  function changePokemon2(event) {
    selPokemon = $(this).val();
    $("[name=\'pv_pokemon2\']").val(pokemons[selPokemon]["pv"]);
    $("[name=\'defense_pokemon2\']").val(pokemons[selPokemon]["defense"]);
    $("[name=\'attaque_pokemon2\']").val(pokemons[selPokemon]["attaque"]);
  }

  $(document).ready(function() {
    $("#pokemon1").on("change", changePokemon1);
    $("#pokemon2").on("change", changePokemon2);
  });

  </script>
';

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
        <select id="pokemon1" name="pokemon1" <?php echo isset($form_error['pokemon1']) ? 'class="error"' : ''; ?>>
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
        <select id="pokemon2" name="pokemon2" <?php echo isset($form_error['pokemon2']) ? 'class="error"' : ''; ?>>
          <?php
            foreach($pokemons as $pokemon => $stats) {
              echo '<option value="' . $pokemon . '" ' . ($pokemon == 'Bulbizarre' ? 'selected' : '') . '>' . $pokemon . '</option>';
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

if (count($_GET) == 0) {
  echo "<h2>Veuillez sélectionner vos pokemons et lancez le combat</h2>";
  exit;
}

$tour = 0;

$nom_pokemon1 = $_GET['pokemon1'];
$pokemon1 = $pokemons[$nom_pokemon1];

// stats customs
$pokemon1["pv"] = $_GET['pv_pokemon1'];
$pokemon1["defense"] = $_GET['defense_pokemon1'];
$pokemon1["attaque"] = $_GET['attaque_pokemon1'];

$nom_pokemon2 = $_GET['pokemon2'];
$pokemon2 = $pokemons[$nom_pokemon2];

// stats customs
$pokemon2["pv"] = $_GET['pv_pokemon2'];
$pokemon2["defense"] = $_GET['defense_pokemon2'];
$pokemon2["attaque"] = $_GET['attaque_pokemon2'];

echo "<h2>$nom_pokemon1 affronte $nom_pokemon2</h2>";

//echo "Date : " . date('d/m/Y : H:i:s');

// Boucle de combat
do {
  echo "<h2> Tour : " . ++$tour . " à " . date('H:i:s') . "</h2>";

  // pokemon1 attaque pokemon2
  echo "<h3>$nom_pokemon1 attaque $nom_pokemon2</h3>";
  if ($pokemon1['attaque'] >= $pokemon2['defense']) {
    // L'attaque est supérieure à la défense : pokemon1 touche
    $coup = $pokemon1['attaque'] - $pokemon2['defense'] + 1; // La valeur du coup est la différence entre l'attaque et la défense
    $pokemon2['pv'] -= $coup;
    echo "<p>$nom_pokemon2 perd $coup PV, il lui reste " . $pokemon2['pv'] . " PV</p>";
  } else {
    // La défense est supérieure à l'attaque, pokemon1 prend la moitié du coup et la défense baisse un peu
    $coup = ($pokemon2['defense'] - $pokemon1['attaque']) / 2;
    $pokemon1['pv'] -= $coup;
    $pokemon2['defense'] -= 1;
    echo "<p>$nom_pokemon2 perd 1 Points de défense, il lui reste " . $pokemon2['defense'] . " Points de défense</p>";
    echo "<p>$nom_pokemon1 râte son attaque ! Il perd $coup Points de vie, il lui reste " . $pokemon1['pv'] . " Points de vie</p>";
  }

  if ($pokemon2['pv'] <= 0) // S'il n'y a pas d'accolades après un if, seule la première instruction est filtrée par le if
    echo "<p>$nom_pokemon2 est KO !</p>";
  if ($pokemon1['pv'] <= 0)
    echo "<p>$nom_pokemon1 est KO !</p>";

  // Et maintenant la contre-attaque : à vous de jouer !
  // pokemon2 attaque pokemon1
  echo "<h3>$nom_pokemon2 attaque $nom_pokemon1</h3>";
  if ($pokemon2['attaque'] >= $pokemon1['defense']) {
    // L'attaque est supérieure à la défense : pokemon2 touche
    $coup = $pokemon2['attaque'] - $pokemon1['defense'] + 1; // La valeur du coup est la différence entre l'attaque et la défense
    $pokemon1['pv'] -= $coup;
    echo "<p>$nom_pokemon1 perd $coup PV, il lui reste " . $pokemon1['pv'] . " PV</p>";
  } else {
    // La défense est supérieure à l'attaque, pokemon2 prend la moitié du coup et la défense baisse un peu
    $coup = ($pokemon1['defense'] - $pokemon2['attaque']) / 2;
    $pokemon2['pv'] -= $coup;
    $pokemon1['defense'] -= 1;
    echo "<p>$nom_pokemon1 perd 1 Points de défense, il lui reste " . $pokemon1['defense'] . " Points de défense</p>";
    echo "<p>$nom_pokemon2 râte son attaque ! Il perd $coup Points de vie, il lui reste " . $pokemon2['pv'] . " Points de vie</p>";
  }

  if ($pokemon2['pv'] <= 0) // S'il n'y a pas d'accolades après un if, seule la première instruction est filtrée par le if
    echo "<p>$nom_pokemon2 est KO !</p>";
  if ($pokemon1['pv'] <= 0)
    echo "<p>$nom_pokemon1 est KO !</p>";

} while ($pokemon1['pv'] > 0 && $pokemon2['pv'] > 0); // === !($pikachu['pv'] <= 0 || $bulbizarre['pv'] <= 0)



// Ajoutons quelques baies pour restaurer des Points de Vies
$pv_baie_rouge = 50;
$pv_baie_noire = 30;

// Bulbizarre mange une baie rouge
// Pikachu mange une baie noire

?>


</body>
</html>
