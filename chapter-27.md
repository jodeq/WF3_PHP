# Upload et système de fichiers / PHP7

## Variable superglobale `$_FILES`

Contrairement aux données "texte", les fichiers envoyés via un formulaire ne sont pas disponible dans la variable `$_POST` mais dans la variable `$_FILES`.

La variable globale `$_FILES` contiendra toutes les informations sur les fichiers téléchargés.

Si vous avez par exemple un `<input type="file" name="mon_fichier" />` dans votre formulaire, une fois envoyé vous obtiendrez alors en PHP:
```
Array     
(     
    [mon_fichier] => Array     
    (     
        [name] => MonFichierOriginal.jpg                       // Nom original du fichier uploadé via le formulaire
        [type] => image/jpg                                    // Type "mime" du fichier, plus fiable que l'extension
        [tmp_name] => chemin_complet_du_fichier_sur_le_serveur // Chemin complet du fichier uploadé sur le serveur
        [error] => 0                                           // Un code d'erreur si il y a une erreur, 0 si tout est ok
        [size] => 1000                                         // La taille en octets du fichier uploadé
    )     
)
```
En parcourant la variable `$_FILES` avec un `foreach` par exemple, vous pouvez parcourir l'ensemble des champs File et leur contenu envoyé.

## Upload de fichiers en PHP : Les particularités du formulaire

Un formuaire HTML permet à l'utilisateur d'envoyer des données "texte" mais il permet également l'envoie de fichiers binaires s'il respectes certaines conditions.

* La balise `form` doit avoir un attribut `enctype` de valeur `multipart/form-data`
* La méthode doit être `POST`
* Un champ de type `file` doit être présent, avec un attribut `name`

L'input de type `file` n'a pas d'attribut `value` (on ne peut donc pas lui donner une valeur par défaut, par sécurité !)

Rappel de la structure du formulaire :
```
<!-- L'attribut enctype doit absolument être "multipart/form-data", et la méthode doit être POST -->
<form method="POST" enctype="multipart/form-data">
    <!-- La balise input type="file" permet de sélectionner un fichier sur son ordinateur. Ne pas oublier l'attribut "name"-->
    <input type="file" name="my_pic" />
    <input type="submit" value="Envoyer"/>
</form>
```

## Sécurité et attaque s possibles

Permettre l'upload d'un fichier à l'utilisateur peut s'avérer risqué. Tout comme avec les données envoyées dans `$_POST`, il ne faut jamais faire confiance aux données envoyées dans `$_FILES`. Un utilisateur malveillant pourrait manipuler le nom du fichier pour réaliser des attaques XSS, manipuler l'extension pour permettre l'upload de fichiers exécutables ou plus simplement, charger des fichiers ultra-volumineux qui ralentirait le serveur.

Il est donc impératif de se protéger finement lorsqu'un système ouvert de chargement de fichier sur le serveur est nécessaire.

Par exemple :
* toujours renommer les fichiers envoyés
* s'assurer que le nom respecte les espaces et caractères spéciaux dans le nom du fichier
* ne pas faire confiance à l'extension du fichier

## Système de fichiers en PHP : CRUD sur des fichiers

### CRUD (Create - Read - Update - Delete) sur des fichiers

PHP permet d'intervenir sur le système de fichiers.

On peut se représenter la façon dont PHP modifie les fichiers en imaginant qu'on souhaite éditer un document Word :

#### 1. J'ouvre le fichier.

C'est comme si j'ouvrais mon document Word.
La fonction `fopen()` renvoie un objet ressource permettant de manipuler un fichier.
On l'utilise ainsi :
```
myfile = fopen('/var/www/myfile', 'r');
```
Le premier paramètre est le chemin du fichier à ouvrir, le deuxième le mode de lecture.
On peut retenir trois modes d'ouverture, qui suffiront dans de nombreux des cas. Pour les autres modes, se référer à [la documentation](http://php.net/manual/fr/function.fopen.php).
```
"r" : "read-only". Ouvre le ficher en lecture seule.
"r+" : Ouvre le ficher pour l'écriture et la lecture. (un "niveau" de plus que "r")
"w+" : "write". Ouvre le fichier pour l'écriture et la lecture, et commence par le vider. Si le fichier n'existe pas, PHP tente de le créer.
"a" : "append". Ouvre le fichier en écriture seule (voir un exemple plus loin), et place le pointeur à la fin du fichier.
"a+" : "append". Ouvre le fichier en lecture et écriture, et place le pointeur à la fin du fichier.
```

#### 2. Je lis et/ou j'écris dans le fichier.

Comme on le fait dans un document Word, on modifie ce qu'on souhaite dans le fichier.
Bien sûr, si le fichier a été ouvert en lecture seule (`"r"`), on ne peut que le consulter.

En PHP, on modifie un fichier à l'aide de la fonction `fwrite()`.
Par exemple, on pourrait ajouter une ligne à un fichier de log :
```
// Ouvrir le fichier et placer le pointeur (le curseur) à la fin
$fileHandler = fopen('/var/log/db.log', 'a+');
// Ajouter une ligne pour la date d'aujourd'hui
$text = date('Y-m-d H:i:s') . ' : Une connexion a été ouverte';
fwrite($fileHandler, $text);
```
Pour lire le contenu d'un fichier, on utilise la fonction `fread()`.
```
// Ouvrir le fichier et placer le pointeur au début
$fileHandler = fopen('/var/log/db.log', 'r');
// Enregistrer les dix premiers caractères
$start = fread($fileHandler, 10);
// Afficher le résultat
echo $start;
```
#### 3. Je "ferme" le fichier.

Revient à fermer Word et faire cesser toute lecture et/ou modification du fichier.

En PHP, il faut donc appeler ces fonctions dans l'ordre, pour manipuler des fichiers :
```
$fileHandler = fopen('/var/log/db.log', 'a+');
fwrite($fileHandler, "du texte à ajouter");
fclose($fileHandler);
```
Les deux fonctions `file_put_contents()` et `file_get_contents()` simplifient ce scénario.
`file_get_contents()` revient à appeler, dans l'ordre, les fonctions `fopen()`, `fread()` et `fclose()`.

Le code suivant récupère, dans la variable `$fileContent`, la totalité du fichier log.txt.
```
$fileContent = file_get_contents('log.txt');
```
On peut aussi choisir de lire une partie seulement du fichier.
Le code suivant va enregistrer dans la variable `$fileSection` les dix caractères à la vingtième position du fichier log.txt (les caractères 20 à 29) :
```
$fileSection = file_get_contents('.log/.txt', NULL, NULL, 20, 10);
```
De même, la fonction `file_put_contents()` simplifie l'écriture dans un fichier.
Exemple d'utilisation :
```
$file = 'fruits.txt';
// Récupère l'intégralité du contenu du fichier
$fruits = file_get_contents($file);
// Ajoute une ligne
$fruits .= "Cerise\n";
// Écrit le contenu mis à jour dans le fichier ouvert
file_put_contents($file, $fruits);
```
Enfin, PHP permet de supprimer un fichier via la fonction suivante:
```
unlink('log.txt');
```

## Système de fichiers en PHP : Fonctions internes utiles

PHP permet de manipuler sans grande difficultés le système de fichiers dans lequel il se trouve.
Voici quelques fonctions qui vous seront utiles pour cela :

### Vérifier si un fichier existe

Avant de créer ou de lire un fichier, on souhaite parfois vérifier son existence pour éviter des erreurs.
C'est le rôle de la fonction `file_exists`.
La fonction `file_exists()` renvoie un booléen. Ainsi, on peut écrire ceci :
```
$img = 'cats';
if(!file_exists('./public/img/thumbnail_' . $img . '.jpg')) {
    /* Il n'existe pas de miniature ("thumbnail") pour l'image "cats.jpg" */
    myFunctionCreateThumbnail($img); /* Une fonction imaginaire */
}
```
### Changer les permissions d'un fichier

Il est parfois nécessaire de changer les permissions (autorisations de lecture, d'écriture et d'exécution) d'un fichier.
On utilisera pour cela la fonction `chmod()`.

Imaginons qu'on aie uploadé un fichier sur le serveur, à la suite d'une saisie de formulaire. La variable `$fileUploaded` contient maintenant le chemin de ce fichier sur le serveur.
Il faut maintenant autoriser PHP à modifier ou supprimer ce fichier.
```
// Donne au serveur tous les droits sur le fichier
chmod($fileUploaded, 0777);
```
### Vérifier l'existence d'un répertoire

Pour ne pas provoquer d'erreur lors de la création d'un fichier, on peut vérifier l'existence du dossier dans lequel on veut opérer.
Le code suivant vérifie l'existence du dossier /var/uploads/site.
```
if(is_dir('/var/uploads/site')) {
    /* Le répertoire existe */
} else {
    /* Le répertoire n'existe pas */
}
```
Initialement, cette fonction permet de vérifier si le chemin passé en paramètre est bien un dossier. Cela signifie que dans notre exemple, si "site" est un fichier, la fonction renverra false également.
On contourne alors l'usage original de la fonction pour tester l'existence d'un dossier.
Vérifier l'existence d'un fichier

De même, on peut vérifier l'existence d'un fichier, à l'aide de la fonction `is_file`.
Elle prend en paramètre le nom du fichier à vérifier.

### Copier un fichier

Il est parfois utile de copier un fichier.
Rien de plus simple en PHP : `copy($fichierSource, $fichierDestination);`

### Déplacer ou renommer un fichier

La fonction permettant de déplacer un fichier en PHP est `rename`.
Pour placer le fichier img.png dans le répertoire parent, on utilisera le code suivant :
```
rename('./img.png', '../img.png');
```
De la même manière que la fonction mv en ligne de commande, cette fonction permet de renommer un fichier, mais également de le déplacer, en partant du principe que le chemin complet du fichier fait parti de son nom.

### Obtenir le nom du dossier courant

On souhaite parfois récupérer le nom du répertoire courant, par exemple lorsqu'on utilise des templates.
En PHP, la constante prédéfinie `__DIR__` nous donne ce renseignement.
La constante `__FILE__`, quant à elle, contient le chemin du fichier courant.
Pour obtenir le dossier dans lequel un fichier existe, on peut utiliser la fonction `dirname`.
```
/* Les deux lignes suivantes produiront le même résultat */
echo dirname(__FILE__);
echo __DIR__;
```

### Connaître la date de dernière modification d'un fichier

On peut obtenir cette information en passant le chemin du fichier à la fonction `filemtime`.
```
$filename = 'access.log';
if (file_exists($filename)) {
    echo "$filename a été modifié le " . date ("d/m/Y à H:i:s.", filemtime($filename));
}
```
Une liste exhaustive des fonctions PHP permettant d'intervenir sur le système de fichiers peut être trouvée [ici](http://php.net/manual/fr/ref.filesystem.php).


## PHP7 : Performances

La version majeure actuelle de PHP est la 7.1.
Elle est basée sur PHPNG project (PHP Next-Gen) qui a été mené par ZEND pour améliorer les performances de PHP.

PHP6 a été abandonnée car l'implémentation d'Unicode a posé de nombreux problèmes, à la fois techniques et communautaires. De ce fait, l'idée a été de passer directement à PHP7 avec de nouvelles perspectives.

### Benchmark

PHP 7 est en moyenne deux fois plus rapide que son prédécesseur. C'est la principale promesse faite par Zend et les contributeurs principaux du projet PHP.
Selon les benchmarks, la mise à jour des sites internets vers PHP7 pourrait accroître les performances de 25% à 70% sans changer une seule ligne de code.

À titre d'exemple, le CMS le plus utilisé, Wordpress (version 4.1) voit ses performances doublées.
Drupal (version 7) quant à lui est plus rapide de 70%.
PHP7 se veut aussi performant que HHVM, la technologie PHP de Facebook.

Côté utilisation mémoire, PHP7 affiche en moyenne une consommation en baisse de 50%.

### Quelques réserves

Il est normal et nécessaire de relativiser ces résultats et de bien regarder les benchmarks auxquels on peut faire dire ce que l'on veut. Le test final sera de tester les performances de son propre site, en situation réelle, pour avoir sa propre idée des gains apporté par PHP7.

Malgré tout, cela est très bon et c'est une bonne chose pour PHP, souvent critiqué pour ses performances médiocres par le passé.

## PHP7 : Nouvelles fonctionnalités

Outre les performances accrues de PHP7 par rapport à son prédécesseur, celui-ci apporte également quelques nouveautés utiles présentées ici:

### Spaceship operator : <=>

Parmi les nouvelles fonctionnalités de PHP7, nous avons l'opérateur ̀`spaceship`.

C'est un nouvel opérateur (comme <, >, ==, ===, >=, <=) qui retourne:

* 0 si les deux opérandes sont égaux,
* 1 si celui de gauche est plus grand
* -1 si celui de droite est plus grand.

Son utilité est fréquente, particulièrement dans les algorithmes de tri.

Exemple d'utilisation :
```
// Integers
echo 1 <=> 1; // 0
echo 1 <=> 2; // -1
echo 2 <=> 1; // 1

// Floats
echo 1.5 <=> 1.5; // 0
echo 1.5 <=> 2.5; // -1
echo 2.5 <=> 1.5; // 1

// Strings
echo "a" <=> "a"; // 0
echo "a" <=> "b"; // -1
echo "b" <=> "a"; // 1

echo "a" <=> "aa"; // -1
echo "zz" <=> "aa"; // 1
```

### Null coalesce operator : ??

Ce nouvel opérateur répond à un besoin constant: le fait de tester l’existence d'une variable et de lui fournir une valeur par défaut si elle n'existe pas. Jusque là il n'existait pas de méthode plus courte que celle-ci (déjà raccourcie par l'utilisation de condition ternaire):
```
$var = isset($maVar) ? $maVar : 'valeur_par_defaut';
```
L’opérateur ?? permet de raccourcir et rendre plus compréhensible cette instruction.
```
$var =  $maVar ?? 'valeur_par_defaut';
```
Ces 2 instructions font exactement la même chose!

### Scalar Type Declarations and Return Types

Les types de retour, c'est la plus grande nouveauté de PHP7. En effet, il est désormais possible de spécifier le type que l'on attend dans la signature d'une fonction ou d'une méthode (gérer en partie par PHP5) ainsi que le type de retour (PHP7). On ajoute `:` suivi du type après les arguments de la fonction pour spécifier le type de retour.

Exemple :
```
//On spécifie que $nom doit être un string et $age un integer
function identite(string $nom, int $age) {
    echo $nom. 'a '.$age.' ans';
}

// On spécifie que la valeur du retour doit être un bolean.
function isAdult(int $age) : bool {
    return $age >= 18;
}
```
Le typage peut fonctionner en 2 modes distincts:

1. Le premier mode est le mode conversion (mode par défaut), les paramètres sont convertis dans le type attendu. Par exemple si l'on attend un `Int` et qu'on passe un `Float(1,5)`, celui-ci sera arrondi et convertie en `Int (1)`.
2. L'autre mode est le mode stricte. Si on passe le mauvais type, cela va générer une erreur fatale.
Pour activer ce mode, il faut déclarer ceci tout en haut du script PHP.
```
<?php
declare(strict_types=1);
```

### Gestion des erreurs

PHP7 modifie la façon dont les erreurs sont signalées.
En PHP5, les erreurs sont affichées selon le mécanisme de rapport d'erreur traditionnel.
Désormais, les erreurs génèrent des types exceptions qui pourront être catchées à travers le mécanisme de try/catch. Si aucun mécanisme n'est mis en place pour gérer l'erreur, alors le gestionnaire d'exception par défaut est appelé (`set_exception_handler()`). Si l'utilisateur n'a pas déclaré de gestionnaire d'exception par défaut alors le mécanisme rapport d'erreur traditionnel sera utilisé.

Voir la [documentation officielle](http://php.net/manual/fr/language.errors.php7.php).

## PHP7 : Fonctions dépréciées

L’arrivée de PHP 7 va supprimer complètement quelques fonctionnalités, marquées comme « deprecated » (dépréciées) depuis plusieurs versions mais qui étaient encore compatibles.
En effet, ces fonctions ou extensions ne sont plus maintenus et/ou sont devenues obsolètes avec de nouvelles fonctions ou extensions apparues depuis.

### Suppression de l'extension ext/mysql

Cette extension est marquée comme dépréciée depuis la version 5.5. Il ne faut donc plus utiliser les fonctions de cette extension. Ce sont les fonctions dont le nom commence par `mysql_`. Cette extension gère notamment la connexion et les requêtes à la base de données.

Il est recommandé d'utiliser les extensions suivantes pour la remplacer :

* ext/mysqli
* ext/pdo_mysql

### Suppression de l'extension ext/ereg

Cette extension est marquée comme dépréciée depuis la version 5.3. Il ne faut donc plus utiliser les fonctions de cette extension. Ce sont les fonctions dont le nom commence par `ereg_`. Cette extension comporte des fonctions sur les expressions régulières.

Il est recommandé d'utiliser les extensions suivantes pour la remplacer :

* ext/pcre

### Autres fonctions dépréciées

On peut citer quelques autres fonctions dépréciées :

* `set_magic_quotes_runtime` et `magic_quotes_runtime`
* `set_socket_blocking`
* `mcrypt_generic_end` - Utiliser `mcrypt_generic_deinit` à la place.
* `mcrypt_ecb`, `mcrypt_cbc`, `mcrypt_cfb and mcrypt_ofb` - Utiliser `mcrypt_encrypt` et `mcrypt_decrypt` à la place.
