# PHP : boucles et fonctions utiles

## Les tableaux

Les tableaux sont une structure de données en mode clé valeur.

Syntaxe
* $array = **array**(cle1 => valeur1, cle2 => valeur2, ...);
* $array = **[**cle1 => valeur1, cle2 => valeur2, ...**]**;

Accès aux valeurs par `$valeur2 = $array['cle2']:`

Ajouter ou modifier une valeur : `$array['cle2'] = valeur2:`

Ajouter une valeur à la fin : `$array[] = valeur;`

## Les boucles

Les boucles permettent de répéter une opération suivant une ou plusieurs conditions particulières.
Il existe 4 types de boucle :

* `for` — elle est composée d'une instruction d'initialisation, d'une condition d'exécution et d'une instruction à exécuter à chaque itération après le bloc d'instruction de la boucle
* `while` — elle est similaire à for si ce n'est qu'elle n'a comme paramètre que la condition d'exécution
* `do…while` — fonctionne comme while, excepté que les instructions qu'elle conditionne sont exécutées 1 fois avant que la condition soit testée
* `foreach` — pour parcourir les éléments d'un tableau ou d'un objet

Exemples
```
/* for */
for ($i = 0; $i < 10; $i++) {
    // instructions
}

/* while */
$i = 0;
while ($i < 10) {
    // instructions
    $i++;
}

/* do…while */
$i = 0;
do {
    // instructions
    $i++;
} while($i < 10);

/* foreach */
$a = array(1, 2, 3);
foreach ($a as $item) {
    // instructions
}
// foreach permet également de récupérer la clé associée à la valeur dans le tableau
$fruits = array('A' => 'abricot', 'B' => 'banane', 'C' => 'cerise');
foreach ($fruits as $lettre => $fruit) {
    echo $lettre . '  :  '  . $fruit; // Affichera pour la première itération "A  : abricot"
}
```

## Fonctions internes de base

Quelques fonctions parmi les plus utilisées en PHP:

* `isset(…)` — retourne true si la variable spécifiée a été définie
* `empty(…)` — retourne true si la variable spécifiée n'existe pas, est égale à 0 ou vide
* `unset(…)` — détruit la variable spécifiée
* `time()` — renvoie le nombre de secondes écoulées depuis le 1er janvier 1970, appelé timestamp
* `date(…)` — permet de renvoyer la date et l'heure actuelle sous le format désiré, ou à partir d'un timestamp précis.
* `count(…)` — calcule le nombre d'éléments contenus dans une tableau (array)
* `strlen(…)` — calcule le nombre de caractères présents dans une chaîne

Exemples
```
/* isset() */
if (!isset($a)) {
    $a = 42; // Si la variable $a n'existe pas déjà, on la défini avec comme valeur 42.
}

/* empty() */
if (!empty($a)) {
    echo $a; // Si la variable $a n'est pas vide, on affiche sa valeur.
}

/* unset() */
unset($a); // Maintenant qu'on a défini puis utilisé la variable $a, on la détruit.

/* time(); */
$t = time(); // affecte à $t le nombre de secondes écoulées depuis le 01/01/1970 à l'instant ou l'instruction est exécutée.

/* date() */
echo date('d / m / Y', $t); // Affichera la date au format JJ / MM / AAAA, par exemple 21 / 10 / 2015.

/* count() */
$b = array('pomme', 'poire', 'abricot');
echo count($b); // Affichera 3, car $b contient 3 valeurs.

/* strlen() */
$c = 'Hello World !';
echo strlen($c); // Affichera 13, car $c contient 13 caractères (cela inclut les espaces)
```

## Gestion des dates

**Date**

La fonction `date` est la fonction de base qui permet par défaut de retourner la date courante.
```
string date ( string $format [, int $timestamp = time() ] )
```

Exemple
```
echo date('c'); // Affichera "2004-02-12T15:19:21+00:00"

echo "Nous sommes à la semaine " . date('W'); // Affichera "Nous sommes à la semaine 42"

echo date('d m Y H:i'); // Affichera "03 09 2015 18:48"

echo date('H\h i\m s\s'); // Affichera "18h 55m 36s"
```

**Timestamp**

La fonction `time()` retourne un timestamp UNIX, qui correspond au nombre de secondes écoulées depuis le 1er Janvier 1970 à 00:00:00 GMT.

Par exemple, le timestamp pour le 12 Février 2004 à 15:19:21 est 1076599161.
De la date au timestamp

Il existe la fonction `strtotime` qui permet de faire l'inverse de la fonction `date`, à savoir transformer un texte en anglais correspondant à une date, en timestamp.
Mais elle permet aussi d'effectuer des manipulations sur les dates en fonction d'un timestamp passé en paramètre, comme par exemple, générer le timestamp avec 2 jours de plus que celui passé en paramètre.
```
int strtotime ( string $time [, int $now = time() ] )
```
Exemple
```
// Usage simple
echo strtotime('now'); // Affichera le timestamp actuel, équivalent à time()
echo strtotime('9 january 2007'); // Affichera le timestamp de cette date, 1168297200

// Usage relatif
echo strtotime('+1 day'); // Affichera le timestamp du lendemain du jour d'exécution du script
echo strtotime('+2 weeks 1 day 5 hours 30 seconds'); // Affichera le timestamp relatif au jour d'exécution du script avec 2 semaines 1 jour 5 heures et 30 secondes d'ajoutées à celui-ci
echo strtotime('last monday'); // Affichera le timestamp du lundi précédent le plus proche du jour d'exécution du script

// Usage avec paramètre
echo strtotime('+6 months', $timestamp); // Affichera le timestamp correspondant à 6 mois après celui passé en paramètre
```
