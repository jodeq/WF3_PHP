<?php
/**
 * Bienvenue dans ce module PHP
 * Nous allons travailler à la réalisation d'un pokedex
 */

include_once('function.php');

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

// tableau de validation
$form_error = [];

// Validation du formulaire
foreach($_POST as $input => $value) {
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

// Vérifions les informations
if (count($form_error) > 0)
  die ("Le combat est reporté pour cause d'erreurs de saisie");

if (count($_POST) == 0) {
  echo "<h2>Veuillez sélectionner vos pokemons et lancez le combat</h2>";
  return;
}

$nom_pokemon1 = $_POST['pokemon1'];
$pokemon1 = $pokemons[$nom_pokemon1];

// stats customs
$pokemon1["pv"] = $_POST['pv_pokemon1'];
$pokemon1["defense"] = $_POST['defense_pokemon1'];
$pokemon1["attaque"] = $_POST['attaque_pokemon1'];

$nom_pokemon2 = $_POST['pokemon2'];
$pokemon2 = $pokemons[$nom_pokemon2];

// stats customs
$pokemon2["pv"] = $_POST['pv_pokemon2'];
$pokemon2["defense"] = $_POST['defense_pokemon2'];
$pokemon2["attaque"] = $_POST['attaque_pokemon2'];

echo "<h2>$nom_pokemon1 affronte $nom_pokemon2</h2>";

// Boucle de combat
do {
  // attaque
  attaque($nom_pokemon1, $pokemon1, $nom_pokemon2, $pokemon2);

  // Si l'un des deux pokemon est KO alors sortie de la boucle via l'instruction break
  if ($pokemon1['pv'] <= 0 || $pokemon2['pv'] <= 0)
    break;

  // contre attaque
  attaque($nom_pokemon2, $pokemon2, $nom_pokemon1, $pokemon1);

} while ($pokemon1['pv'] > 0 && $pokemon2['pv'] > 0); // === !($pikachu['pv'] <= 0 || $bulbizarre['pv'] <= 0)

// Ajoutons quelques baies pour restaurer des Points de Vies
$pv_baie_rouge = 50;
$pv_baie_noire = 30;

// Bulbizarre mange une baie rouge
// Pikachu mange une baie noire

?>
