# PHP : Dynamiser un site HTML

## Structure de contrôle et d'inclusion

Il existe quatre fonctions pour pour l'inclusion de fichier PHP
* `include()` insère le code donné et lance un warning en cas d'échec.
* `include_once()` comme include mais seulement si le fichier n'a pas déjà été ajouté.
* `require()` insère le code donné et lance une erreur fatale en cas d'échec.
* `require_once()` comme le require mais seulement si le fichier n'a pas déjà été ajouté.

Exemples d'inclusion :
```
/**
 * Incluera le contenu de header.php
 * Génère une erreur fatale en cas d'erreur
 */
// Exécute le contenu de header.php
require('header.php');

/**
 * Incluera le contenu de header.php
 * Déclenche un warning en cas d'erreur
 */
// Exécute le contenu de header.php
include('header.php');

/**
 * Le fichier header.php
 * ne sera inclus qu'à la premier instruction.
 */
include_once('header.php');
include_once('header.php');
include_once('header.php');
```

Les fichiers sont inclus selon le chemin fourni ou, à défaut, recherché dans le `include_path` configuré dans PHP.

**Quelques constantes utiles pour l'inclusion**

PHP met à notre disposition des constantes magiques qui peuvent s'avérer très utiles pour l'inclusion :

* `__DIR__` Le répértoire du script actuel
* `__FILE__` Le fichier du script actuel
* `__LINE__` La ligne actuelle dans le script
* `__FUNCTION__` Le nom de la fonction actuelle

Exemple : Inclure le fichier header.php qui se trouve dans le même répertoire le script courant.
```
/**
 * Inclus le fichier header.php qui se situe dans
 * le même répértoire que celui du script
 */

//  Permet d'éviter les ambiguités
include(__DIR__.'/header.php');
```

## Le retour des boucles, fonctions utiles

Les boucles sont exécutées en continue jusqu'à leurs clauses de sortie, si nous n'avons pas écris malgré nous une boucle infinie.

L'instruction `break` permet de sortir d'une structure `for`, `foreach`, `while` et `do-while`.
Nous pouvons l'utiliser pour une recherche par exemple, une fois l'item trouvé, cela ne sert plus à rien de continuer à chercher.

L'instruction `continue` permet de sauter les instructions suivantes de la boucle et de revenir au début du tour suivant

Exemple
```
$array = ['un', 'deux', 'trois', 'quatre'];
while ($i++ < count($array)) {
    if (!($i % 2)) { // évite les membres impairs
        continue;
    }
    echo $array[$i]; // Affichera : deux quatre
}
```

## Les fonctions utilisateurs

**Création d'un fonction**
```
function ma_fonction($var1, $var2) { // signature
  // instructions

  return $resultat;
};
```
Les paramètres sont optionnels et une valeur peut être retournée avec le mot clé `return`.

**Appel d'une fonction**
```
$mon_resultat = ma_fonction($var1, $var2);
```

Exemple d'utilisation de fonctions utilisateurs

Il n'y a pas de limite aux possibilités offertes par les fonctions utilisateurs. Il est possible de faire pratiquement tout.

Exemple : fonction utilisateur pour afficher un menu
```
/*
 * Nom de la fonction : affiche_menu
 * Arguments : $menus (tableaux de menus)
 * Ne retourne aucune valeur
 */
function affiche_menu($menus)
{
    foreach($menus as $key => $value) {
        echo '<li>'.$value.'</li>';
    }
}

$menus = array('menu1', 'menu2');
// Exécute la fonction utilisateur
affiche_menu($menus);
```

## La portée des variables

La portée d'une variable est son existence au sein d'un contexte (script principal ou fonction).
Selon le contexte dans lequel une variable est déclarée, sa portée en dépendra.
En effet pour la majorité des variables, la portée concerne la totalité d'un script PHP.
Les variables sont accessibles partout dans le script après leur déclaration.

Mais à l'intérieur d'une fonction utilisateur, les variables définies sont locale à la fonction, c'est-à-dire qu'elles ne sont accessibles qu'à l'intérieur de la fonction elle-même.
De même, à l'intérieur d'une fonction, on a pas accès aux variables déclarées à l'extérieur de la fonction.
Seules les variables passées en argument et les variables super globales sont accessibles dans une fonction.

Exemple :
```
$a = 1; /* portée globale */

function toto()
{
    $b = 2;  /* portée locale*/

    echo $a;
}

toto();
echo $b;
```
A l'éxécution du code ci-dessus, nous aurons 2 erreurs de niveau warning.
En effet, lorsque la fonction toto() est appelée, elle vas essayer d'afficher la variable $a.
Or la variable $a est déclarée hors de la fonction et elle n'est pas non plus passée en argument à la fonction.
La deuxième erreur se produit lorsqu’on veut afficher la variable $b. En effet, elle a été déclarée au sein de la fonction toto() et n'est donc pas accessible à l'extérieur.
Accès à une variable globale dans une fonction

Avec le mot clé global, on peut accéder aux variables globales déclarées en dehors de la fonction.

Exemple :
```
$a = 1; /* portée globale */

function toto()
{
    global $a;

    echo $a;
}

//Affiche 1
toto();
```
On peut aussi accéder à ces variables avec la variable super globale $GLOBALS. Le tableau $GLOBALS est un tableau associatif avec le nom des variables globales comme clé et les valeurs des éléments du tableau comme valeur des variables.

Exemple :
```
$a = 1; /* portée globale */

function toto()
{
    echo $GLOBALS['a'];
}

//Affiche 1
toto();
```

**Les variables statiques**

Une variable statique est une variable qui a une portée locale uniquement (dans une fonction), et sa valeur n'est pas réinitialisée lorsqu’on appelle de nouveau cette fonction, contrairement aux autres variables locales qui seront perdus lorsqu'on sort de la fonction.

On déclare une variable statique avec le mot clé static.

Exemple : Compter le nombre de fois que qu'une fonction est appelée
```
function self_count()
{
    static $a = 0; // $a est initialisé à 0 lors du premier appel de la fonction mais n'est plus réinitialiser lors des autres appels.
    $a++;

    return $a;
}

echo self_count(); //Affiche 1
echo self_count(); //Affiche 2
echo self_count(); //Affiche 3
```
