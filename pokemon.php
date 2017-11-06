<?php
/**
 * Bienvenue dans ce module PHP
 * Nous allons travailler à la réalisation d'un pokedex
 */

// Pikachu
$attaque_pikachu = 15;
$defense_pikachu = 10;
$pv_pikachu = 25;

$pikachu = [
  'pv' => 25,
  'attaque' => 15,
  'defense' => 10
];

// Bulbizarre
$attaque_bulbizarre = 8;
$defense_bulbizarre = 20;
$pv_bulbizarre = 30;

$bulbizare = [
  'pv' => 30,
  'attaque' => 8,
  'defense' => 20
];

// pikachu attaque bulbizarre
echo "<p>Pikachu attaque bulbizarre</p>";
if ($pikachu['attaque'] >= $bulbizare['defense']) {
  // L'attaque est supérieure à la défense : pikachu touche
  $coup = $pikachu['attaque'] - $bulbizare['defense'] + 1; // La valeur du coup est la différence entre l'attaque et la défense
  $bulbizare['pv'] -= $coup;
  echo "<p>Bulbizarre perd $coup PV, il lui reste " . $bulbizare['pv'] . " PV</p>";
} else {
  // La défense est supérieure à l'attaque, pikachu prend la moitié du coup et la défense baisse un peu
  $coup = ($bulbizare['defense'] - $pikachu['attaque']) / 2;
  $pikachu['pv'] -= $coup;
  $bulbizare['defense'] -= 1;
  echo "<p>Bulbizarre perd 1 Points de défense, il lui reste " . $bulbizare['defense'] . " Points de défense</p>";
  echo "<p>Pikachu râte son attaque ! Il perd $coup Points de vie, il lui reste " . $pikachu['pv'] . " Points de vie</p>";
}

if ($bulbizare['pv'] <= 0) // S'il n'y a pas d'accolades après un if, seule la première instruction est filtrée par le if
  echo "<p>Bulbizarre est KO !</p>";
if ($pikachu['pv'] <= 0)
  echo "<p>Pikachu est KO !</p>";

// Et maintenant la contre-attaque : à vous de jouer !
// bulbizarre attaque pikachu
echo "<p>Bulbizarre attaque Pikachu</p>";
if ($bulbizare['attaque'] >= $pikachu['defense']) {
  // L'attaque est supérieure à la défense : bulbizarre touche
  $coup = $bulbizare['attaque'] - $pikachu['defense'] + 1; // La valeur du coup est la différence entre l'attaque et la défense
  $pikachu['pv'] -= $coup;
  echo "<p>Pikachu perd $coup PV, il lui reste " . $pikachu['pv'] . " PV</p>";
} else {
  // La défense est supérieure à l'attaque, bulbizarre prend la moitié du coup et la défense baisse un peu
  $coup = ($pikachu['defense'] - $bulbizare['attaque']) / 2;
  $bulbizare['pv'] -= $coup;
  $pikachu['defense'] -= 1;
  echo "<p>Pikachu perd 1 Points de défense, il lui reste " . $pikachu['defense'] . " Points de défense</p>";
  echo "<p>Bulbizarre râte son attaque ! Il perd $coup Points de vie, il lui reste " . $bulbizare['pv'] . " Points de vie</p>";
}

if ($bulbizare['pv'] <= 0) // S'il n'y a pas d'accolades après un if, seule la première instruction est filtrée par le if
  echo "<p>Bulbizarre est KO !</p>";
if ($pikachu['pv'] <= 0)
  echo "<p>Pikachu est KO !</p>";

// Ajoutons quelques baies pour restaurer des Points de Vies
$pv_baie_rouge = 50;
$pv_baie_noire = 30;

// Bulbizarre mange une baie rouge
// Pikachu mange une baie noire
?>
