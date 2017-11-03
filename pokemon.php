<?php
/**
 * Bienvenue dans ce module PHP
 * Nous allons travailler à la réalisation d'un pokedex
 */

// Pikachu
$attaque_pikachu = 15;
$defense_pikachu = 10;
$pv_pikachu = 25;

// Bulbizarre
$attaque_bulbizarre = 8;
$defense_bulbizarre = 20;
$pv_bulbizarre = 30;

// pikachu attaque bulbizarre
echo "<p>Pikachu attaque bulbizarre</p>";
if ($attaque_pikachu >= $defense_bulbizarre) {
  // L'attaque est supérieure à la défense : pikachu touche
  $coup = $attaque_pikachu - $defense_bulbizarre + 1; // La valeur du coup est la différence entre l'attaque et la défense
  $pv_bulbizarre -= $coup;
  echo "<p>Bulbizarre perd $coup PV, il lui reste $pv_bulbizarre PV</p>";
} else {
  // La défense est supérieure à l'attaque, pikachu prend la moitié du coup et la défense baisse un peu
  $coup = ($defense_bulbizarre - $attaque_pikachu) / 2;
  $pv_pikachu -= $coup;
  $defense_bulbizarre -= 1;
  echo "<p>Bulbizarre perd 1 Points de défense, il lui reste $defense_bulbizarre Points de défense</p>";
  echo "<p>Pikachu râte son attaque ! Il perd $coup Points de vie, il lui reste $pv_pikachu Points de vie</p>";
}

if ($pv_bulbizarre <= 0) // S'il n'y a pas d'accolades après un if, seule la première instruction est filtrée par le if
  echo "<p>Bulbizarre est KO !</p>";
if ($pv_pikachu <= 0)
  echo "<p>Pikachu est KO !</p>";

// Et maintenant la contre-attaque : à vous de jouer !
// bulbizarre attaque pikachu
echo "<p>Bulbizarre attaque Pikachu</p>";
if ($attaque_bulbizarre >= $defense_pikachu) {
  // L'attaque est supérieure à la défense : bulbizarre touche
  $coup = $attaque_bulbizarre - $defense_pikachu + 1; // La valeur du coup est la différence entre l'attaque et la défense
  $pv_pikachu -= $coup;
  echo "<p>Pikachu perd $coup PV, il lui reste $pv_pikachu PV</p>";
} else {
  // La défense est supérieure à l'attaque, bulbizarre prend la moitié du coup et la défense baisse un peu
  $coup = ($defense_pikachu - $attaque_bulbizarre) / 2;
  $pv_bulbizarre -= $coup;
  $defense_pikachu -= 1;
  echo "<p>Pikachu perd 1 Points de défense, il lui reste $defense_pikachu Points de défense</p>";
  echo "<p>Bulbizarre râte son attaque ! Il perd $coup Points de vie, il lui reste $pv_bulbizarre Points de vie</p>";
}

if ($pv_bulbizarre <= 0) // S'il n'y a pas d'accolades après un if, seule la première instruction est filtrée par le if
  echo "<p>Bulbizarre est KO !</p>";
if ($pv_pikachu <= 0)
  echo "<p>Pikachu est KO !</p>";

// Ajoutons quelques baies pour restaurer des Points de Vies
$pv_baie_rouge = 50;
$pv_baie_noire = 30;

// Bulbizarre mange une baie rouge
// Pikachu mange une baie noire
?>
