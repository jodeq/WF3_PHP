# PHP : Démarrage et syntaxe

**[PHP](http://php.net/manual/fr/intro-whatis.php)** est un acronyme récursif signifiant ̀ PHP Hypertext Preprocessor`.

Hello World :
```
<!DOCTYPE HTML>
<html>
<head>
  <title>Exemple</title>
</head>
<body>

  <?php
    echo "Bonjour, je suis un script PHP !";
  ?>

</body>
</html>
```

Le code PHP est contenu entre une balise d'ouverture `<?php` ou `<?` et une balise fermante optionnelle `?>`.

## Ressources

* [php.net](http://php.net/manual/fr/)
* [W3Schools](https://www.w3schools.com/php/default.asp)
* [OpenClassrooms](https://openclassrooms.com/)
* [GrafikArt](https://www.grafikart.fr/)
* [StackOverflow](https://stackoverflow.com/questions/tagged/php)

## Les types de données

* [`boolean`](http://php.net/manual/fr/language.types.boolean.php)
* [`integer`](http://php.net/manual/fr/language.types.integer.php)
* [`float`](http://php.net/manual/fr/language.types.float.php)
* [`string`](http://php.net/manual/fr/language.types.string.php)
* [`array`](http://php.net/manual/fr/language.types.array.php)
* [`object`](http://php.net/manual/fr/language.types.object.php)
* [`resource`](http://php.net/manual/fr/language.types.resource.php)
* [`null`](http://php.net/manual/fr/language.types.null.php)

## Les variables

Les variables PHP commencent toujours par le symbole `$` exemple `$myar`.

Règles de nommage :
 * Commencer par une lettre ou un underscore `_`
 * Contenir un ensemble de lettres, de chiffres et du `_`
 * Ne pas être `$this` qui est une variable réservée de PHP

exemple
```
$a = 'Hello World !';
$b = $a; // $b vaut alors 'Hello World !', comme $a

$c = 21;
$d = $c * 2; // $d vaut alors 42

echo $a; // Affichera: Hello World !

echo 'Hello !'; // Affichera directement: Hello !
```

## Les instructions

Une instruction représente une action à effectuer par PHP. Chaque instruction se termine par `;`. les blocs de code sont encadrés par des accolades `{ }`.

exemple
```
if (!isset($a)) {
    $a = 4;
    echo $a;
    /*
        ici, à l'exception de la condition, nous avons 2 instructions, terminées par un point-virgule.
        la première affecte une valeur à une variable, la seconde affiche cette variable
    */
}
```

## La concaténation

l'opérateur de concaténation est le `.` il permet de mettre bout à bout deux chaînes de caractères.

exemple
```
/* Opérateur de concaténation */
$a = 'Hello ';
$b = $a . 'World !'; // $a ne change pas mais $b vaut désormais "Hello World !"

/* Opérateur d'affectation concaténant */
$c = 'Hello ';
$c .= 'World !'; // On concatène cette chaîne à la précédente, $c vaut désormais "Hello World !"

/* Il est tout à fait possible de combiner les deux */
$d = 'Hello ';
$e = 'World ';
$d .= $e . '!'; // $e ne change pas, mais $d vaut désormais "Hello World !"
```

## Les conditions

structure
```
if (<condition>) {                   // Si
  <bloc d'instructions du if>
} elseif (<autre condition>) {        // Sinon si
  <bloc d'instructions du elsif>
} elseif (<autre autre condition>) {  // Sinon si
  <bloc d'instructions du elsif>
} else {                             // Sinon
  <bloc d'instrucions du else>
}
```
* Les `elseif` et le `else` sont optionnels
* Autant de `elseif` que l'on veut
* le `else` toujours en dernier

Exemple
```
if ($soleil == "levé" && $heure == 6) {
  echo "C'est l'été et il fait jour !";
} elsif ($soleil != "levé" && $heure == 8) {
  echo "C'est l'hiver et le soleil n'est pas encore levé";
} else {
  echo "Quelle heure est-il ?";
}
```
