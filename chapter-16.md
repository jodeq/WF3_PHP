# PHP - les tableaux complexes et les formulaires

## Les tableaux multidimensionnels

Les tableaux multidimensionnels sont des tableaux ayant comme valeurs un ou plusieurs tableaux imbriqués, ce éventuellement sur plusieurs niveaux (dimensions). Ce type de données permet de stocker des informations reliées de manière structurée, facile à parcourir.

Exemple d'utilisation simple d'un tableau multidimensionnel :

Nous voulons créer un tableau multidimensionnel pour stocker la matrice (tableau à 2 dimensions) d'un jeu de morpion. Nous aurons donc besoin d'un tableau de tailles 3x3.
```
$tableauMultidimensionnel = array();
$tableauMultidimensionnel[0] = array('X','O','X');
$tableauMultidimensionnel[1] = array('X','O','O');
$tableauMultidimensionnel[2] = array('O','O','X');
```
Explications : Concrètement nous avons créé un tableau de tableaux. Nous avons associé un nouveau tableau de 3 cases à chaque clé (ligne) du tableau. Pour accéder à une valeur de ce tableau multidimensionnel, nous devons indiquer tout d'abord la clé du tableau principal et ensuite indiquer la clé pour le tableau associé. Ce qui nous donne la syntaxe suivante : `$tableauMultidimensionnel[numéroDeLigne][numéroDeColonne]`.

Pour afficher la valeur de la case du milieu du jeu de morpion :
```
echo $tableauMultidimensionnel[1][1];
// Affiche O
// Les index des tableaux en PHP commencent à 0, donc 1 représenté la 2e élément dans le tableau.
```
Imaginons un exemple plus complexe, nous voulons stocker dans un tableau une liste de personnes, et chaque personne a un prénom, nom, email et une liste de numéros de téléphone. Pour cela on doit créer un tableau multidimensionnel à 3 dimensions. Dans la première dimension, nous allons mettre les personnes. Dans la 2e dimension, nous allons mettre ses informations personnelles : prénom, nom et email. Et dans la 3e dimension, nous allons mettre une liste de numéros de téléphone.
```
$personnes = array(
    '0' => array(
        'nom' => 'Dupont',
        'prenom' => 'Pierre',
        'email' => 'pierre.d@gmail.com',
        'telephones' => array(
            'fixe' => '03 00 00 00 00',
            'portable' => '06 00 00 00 00'
        )
    ),
    '1' => array(
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'email' => 'jean.d@gmail.com',
        'telephones' => array(
            'fixe' => '03 00 00 00 00',
            'portable' => '06 00 00 00 00'
        )
    ),
    '2' => array(
        'nom' => 'Dupont',
        'prenom' => 'Marie',
        'email' => 'marie.d@gmail.com',
    ),
  );
```
PHP n'alloue pas de valeur par défaut à un cellule non renseignée. L'erreur la plus courante est l'accès à une cellule d'un tableau multidimensionnel qui n'existe pas. Cela génère une erreur de niveau notice. Nous allons utiliser la fonction isset() afin de savoir si une cellule existe dans un tableau.

Pour afficher les informations de ces personnes :
```
foreach($personnes as $personne) {
    echo 'Nom : '.$personne['nom'].'<br/>';
    echo 'Prénom : '.$personne['prenom'].'<br/>';
    echo 'Email : '.$personne['email'].'<br/>';

    // On vérifie l'existence de la cellule des téléphones
    if (isset($personne['telephones'])) {
        foreach($personne['telephones'] as $telephone) {
            echo 'Téléphone : '.$telephone.'<br/>';
        }
    }
  }
```

## Les formulaires HTML

Les formulaires HTML sont le lien entre les utilisateurs et un site web ou application. Ils permettent à l'utilisateur d'envoyer des données au serveur.

Les principales utilisations d'un formulaire sont :

* Formulaire de contact
* Poster un article ou commentaire
* Sondage
* Remplir un profil et ses préférences utilisateurs

Pour créer un formulaire dans votre page HTML, vous devez utilisez la balise form.
```
<form>
    Contenu de mon form
</form>
```
Par défaut, le formulaire sera envoyé avec la méthode HTTP GET. Pour l'envoyer avec la méthode HTTP POST, vous devez renseigner l'attribut method du formulaire.
```
<form method="POST">
    Contenu de mon form
</form>
```
Par défaut, le formulaire sera envoyé à l'URL courante. Pour changer l'URL de destination, vous devez renseigner l'attribut action du formulaire.
```
<form action="/contact.php">
    Contenu de mon form
</form>
```
Maintenant que nous avons vu comment et où envoyer un formulaire, nous allons voir les différents éléments de formulaires qui permettent à un utilisateur de rentrer des informations. Il existe 3 types d'éléments :

* `Input` (Champs de saisie de texte à une seule ligne et différents types de boutons)
* `Select` (une liste à choix multiples)
* `Textarea` (Zone de saisie de texte multiligne)

Pour plus de détails sur les éléments des formulaires, vous pouvez consulter cet article sur [les balises des formulaires HTML](https://fr.wikibooks.org/wiki/Le_langage_HTML/Formulaires#La_balise_.3Cinput.3E).

Pour créer une zone de texte à une ligne, on doit écrire :
```
<input type="text" />
```
Pour pouvoir fonctionner pleinement, notre balise input a besoin d'un nom pour être reconnaissable par PHP plus tard. Cela se fait par l'attribut name. Grâce au nom donné à l'input, nous pouvons savoir ce que l'utilisateur a renseigné précisément (nom, prénom, email...).
```
<input type="text" name="nom" />
<input type="text" name="prenom" />
<input type="text" name="email" />
```
Exemple d'un formulaire de contact :
```
<form method="POST" action="/contact.php">
    <select name="civilite">
        <option value="Madame">Madame</option>
        <option value="Monsieur">Monsieur</option>
    </select>
    <input type="text" name="pseudo" />
    <textarea name="contenu"></textarea>
    <input type="text" name="email" />
    <input type="submit"/> // Bouton pour envoyer le formulaire
</form>
```

## Les variables superglobales : $_GET et $_POST

La méthode HTTP GET est utilisée par défaut dans un formulaire. Lors de la soumission d'un formulaire, les variables sont passées en clair dans l'URL en suivant le format suivant :
```
http://www.monsite.com/contact.php?pseudo=toto&message=coucou
```
Cette URL veut dire que nous allons transmettre à la page contact.php les couples variable/valeur suivante :

* pseudo = toto
* message = coucou

La première variable d'une URL est toujours précédée du symbole ?. Les autres seront précédées du symbole &.

Dans notre script contact.php nous pouvons récupérer ces variables avec la variable superglobale $_GET.
```
print_r($_GET);
// Accès tous les variables émis en HTTP GET
// Affiche array('pseudo' => 'toto', 'message' => 'coucou')

print_r($_GET['pseudo']);
// Accès à une variable émis en HTTP GET
// Affiche 'toto'
```
La méthode POST transmet les données du formulaire de manière masquée et non cryptée. Nous ne voyons donc pas les données transmises dans l'URL.

Dans notre script contact.php, nous pouvons récupérer ces variables avec la variable superglobale $_POST.
```
print_r($_POST);
// Accès tous les variables émis en HTTP POST
// Affiche array('pseudo' => 'toto', 'message' => 'coucou')

print_r($_POST['pseudo']);
// Accès à une variable émis en HTTP POST
// Affiche 'toto'
```
Quand utiliser la méthode GET ou la méthode POST ?

La méthode GET est utilisée de préférence sauf si on ne veut pas que les variables soient lisibles dans l'URL. La méthode GET est à utiliser lorsque la requête ne modifie pas les données de la base de données du site. C'est le cas, par exemple, pour une page qui affiche les produits en vente sur une boutique en ligne. Dans ce cas, le paramètre passé en GET pourrait permettre de trier les produits du moins cher au plus cher. C'est également un avantage en cas de partage de cette page car nous n'avons plus besoin de soumettre le formulaire de nouveau pour qu'elle soit prise en compte dans la variable superglobale $_GET.

Exemple d'une URL avec paramètre en GET dans un moteur de recherche :
```
http://www.google.fr/search?q=php
```
La méthode POST est à utiliser lorsque l'utilisateur envoie des données au site. Par exemple, un formulaire permettant d'envoyer un commentaire sur un sujet de blog. On utilise également la méthode POST lorsqu'on ne souhaite pas que les variables et leurs valeurs se retrouvent dans les fichiers de log du serveur. On utilisera donc la méthode POST pour un formulaire d'identification..

En résumé, les variables superglobales $_GET et $_POST sont des tableaux de données associatifs.

Voici leurs principales caractéristiques :

* Ils sont sont accessibles partout dans votre script PHP et sont générés avant même que le script ne soit exécuté.
* Les clés des tableaux correspondent aux attributs name des éléments du formulaire et les valeurs aux attributs value.
* Ils sont accessibles en lecture et en écriture. Il est donc possible de les modifier.

## Fonctions sur les chaînes

PHP est livré en standard avec de nombreuses fonctions sur les chaînes de caractères (String). Voici, les fonctions les plus utlisées :

* `echo` - Probablement la fonction la plus utilisée. Elle permet d'afficher une chaîne de caractères.
```
$message = 'Hello world!';
echo $message;
// Affiche Hello world.
```
* `sprintf` - Retourne une chaîne de caractères formatée.
```
$num      = 5;
$location = 'bananier';
$message  = 'Il y a %d singes dans le %s.';
echo sprintf($format, $num, $location); // Affiche 'Il y a 5 singes dans le bananier'.
```
strlen - Retourne la longueur d'une chaîne de caractères.

$message  = 'Hello world!';
echo strlen($message); // Affiche 12

* `strstr` - Trouve la première occurrence dans une chaîne (sensible à la casse) et retourne une nouvelle chaîne allant de la première occurrence jusqu'à la fin de la chaîne.

Même fonction mais insensible à la casse : `stristr`.
```
$message  = 'email@domain.com';
echo strstr($message, '@'); // Affiche '@domain.com'

// A partir de PHP 5.3, ajout d'un second argument pour retourner le début de chaîne avant l’occurrence recherchée
$message  = 'email@domain.com';
echo strstr($message, '@', true); // Affiche 'email'
```

* `strpos` - Recherche la première occurrence de chaine2 dans chaine1 et retourne sa position numérique ou false si non trouvée.

Même fonction mais insensible à la casse : `stripos`.
```
$message  = 'email@domain.com';
echo strpos($message, '@'); // Affiche 5
echo strpos($message, '€'); // Affiche false (Le navigateur n'affiche rien pour false et 1 pour echo dans la fonction echo)
```
* `str_replace` - Remplace toutes les occurrences de chaine1 dans chaine3 par chaine2.

Même fonction mais insensible à la casse : `str_ireplace`.
```
$message  = 'email@domain.com';
echo str_replace('domain', 'gmail', $message); // Affiche email@gmail.com

//Nous pouvons aussi remplacer plusieurs occurences via un tableau
echo str_replace(array('email', 'domain'), array('toto', 'gmail'), $message); // Affiche toto@gmail.com
```
 * `substr` - Retourne le segment de string défini par start et length. Si length n'est pas renseigné, retourne jusqu'à la fin de la chaine

```
echo substr("abcdef", 2, 2); // Affiche "cd"
echo substr("abcdef", 1, 3); // Affiche "bcd"
echo substr("abcdef", 3);    // Affiche "def"

//Exemple de start négatif echo substr("abcdef", -1); // Affiche "f" echo substr("abcdef", -2); // Affiche "ef" echo substr("abcdef", -3, 1); // Affiche "d"
```

* `strrev` -  Inverse une chaîne de caractères
```
echo strrev("Hello world!"); // Affiche "!dlrow olleH"
```
* `explode` - Retourne un tableau de chaînes, chacune d'elle étant une sous-chaîne du paramètre string extraite en utilisant le séparateur delimiter.
```
$motsclés = "php,fonction,chaine,catactere";
$tableau_mots_cles = explode(',', $motsclés);
echo $tableau_mots_cles[0]; //Affiche 'php'
echo $tableau_mots_cles[1]; //Affiche 'fonction'
echo $tableau_mots_cles[2]; //Affiche 'chaine'
echo $tableau_mots_cles[3]; //Affiche 'catactere'
```
* `strtolower` - Renvoie une chaîne en minuscules.
```
$message = 'Hello World';
echo strtolower($message); //Affiche 'hello world';
```
* `strtoupper` - Renvoie une chaîne en majuscules.
```
$message = 'Hello World';
echo strtolower($message); //Affiche 'HELLO WORLD';
```
* `trim` - Enlève les espaces au début et à la fin de la chaîne de caractères..
```
$message = '    Hello World  ';
echo strtolower($message); //Affiche 'Hello World';
```
* `strip_tags` - Supprime les balises HTML et PHP d'une chaîne. En 2e argument on peut autoriser certaines balises
```
$message = '<h1>Hellow World, </h1><p>How are you ?</p>';
echo strip_tags($message); //Affiche 'Hellow World, How are you ?';
echo strip_tags($message, '<h1>''); //Affiche '<h1>Hellow World, </h1>How are you ?';
```

## Validation des données

Lorsqu'un client soumet un formulaire, nous devons manipuler ces données avec précaution afin de récupérer les données utiles. Ceci fait partie de la stratégie de validation de données.
Échapper les données

Un principe en sécurité est de ne jamais faire confiance aux utilisateurs. En effet, comme on ne peut pas faire la différence entre utilisateurs honnêtes et les pirates, il faut être prudent avec toutes les données que vous recevez. Pour éviter les utilisateurs malicieux de faire des injections SQL pour pirater votre base de données, vous devez échapper les données.

Les fonctions natives de PHP permettent d’effectuer ce type d’actions :

* htmlspecialchars()
* htmlentities()
* strip_tags()
* urlencode()
* json_encode()
* mysqli_real_escape_string()
* addslashes()

**Filtrer les données**

Maintenant que vous avez sécurisé les données reçues, il faut filtrer les données pour obtenir des données utiles. En effet, imaginez un utilisateur qui n'a pas rempli un champs obligatoire comme son nom. On doit alors vérifier si la chaîne de caractères n'est pas vide sinon on retourne une erreur.
```
if (strlen($nom) == 0) {
    exit('Votre nom ne doit pas être vide');
}
```
Les vérifications classiques sont les suivantes, et les fonctions pour les vérifier :

* champs obligatoire - strlen()
* un nombre entier - ctype_digit()
* supprimer les balises - strip_tags()
* savoir si une variable représente un email, une url, etc... - filter_var()

Une bonne stratégie en termes de validation de données est de définir ce que vous attendez de vos utilisateurs. Cela peut être un format, un ensemble de valeurs possibles. Le filtrage peut être réalisé à l’aide d’expressions régulières ou encore avec la fonction filter_var, avec un paramètre de filtre de nettoyage :

Exemple : Nous voulons récupérer l'url du site Web d'un utilisateur
```
if (false == filter_var($url, FILTER_VALIDATE_URL)) {
    exit("L'url de votre site n'est pas valide");
}
```
Pour en savoir sur la fonction filter_var, consultez cet [article](http://php.net/manual/fr/function.filter-var.php) ou [celui-ci](http://php.net/manual/fr/filter.filters.sanitize.php).
