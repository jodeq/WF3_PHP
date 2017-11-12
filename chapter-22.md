# Construire un site dynamique

## Organisation des répertoires

Les fichiers d'un site internet sont répartis dans différents dossier selon leurs natures.

Exemple de structure de dossier :
```
├───public               // Éléments publics accessible par une URI
│   └───assets
│       ├───css
│       ├───img
│       └───js
└───resources            // Fichiers de configuration BDD, clés API, ...
    └───librairies       // Librairies tierces

ou

├───www               // Éléments publics accessible par une URI
│   └───inc
│       ├───css
│       ├───img
│       └───js
└───lib               // Librairies tierces, fichiers de configuration BDD, clés API, ...
```
Le dossier `resources` contiendra les librairies tierces utilisées par PHP, les fichiers de configuration d'accès à la base de données, etc. Seul le dossier ̀`public` sera accessible de l'extérieur par une requête HTTP, ce qui sécurise l'accès aux fichiers sensibles (contenant par exemple des identifiants d'accès, des clés d'API, etc).

Un site web peut être comparé à un livre. Il est possible de lire différents chapitres, de passer de l'un à l'autre, de se repérer à l'aide de la table des matières, etc. Il dispose d'une couverture semblable à une "page d'accueil". Un site web statique est comme un livre déjà imprimé, on peut le feuilleter autant de fois qu'on le souhaite, mais son contenu restera le même.

Un site web dynamique, quant à lui, n'a pas d'état fixe. Il est relié à une base de données, qui contient certaines des informations à afficher. Les pages de ce genre de site peuvent être construites pièce par pièce, en "empilant" plusieurs contenus.

## DRY : Don't Repeat Yourself

Imaginons un site web statique comportant un certain nombre de pages, chaque page ayant le même en-tête et le même pied de page. On peut rendre le site dynamique en séparant les contenus dans des fichiers différents, par exemple l'en-tête dans `header.php` et le pied de page dans `footer.php`.

Suit un exemple simpliste, qui illustre l'intérêt de fractionner le code d'une page web.

Une page de ce site pourrait ressemblerait à ceci : `index.php`
```
<?php
    require_once 'inc/header.php';
?>
<article>
    <header>Contact</header>
    <p>
        John Doe
    </p>
    <p>
        (+33) 9 87 42 01 12
    </p>
</article>
<?php
    require_once 'inc/footer.php';
```
Contenu du fichier `header.php` :
```
<html>
    <head>
        <title>Ma page web</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
```
Contenu du fichier `footer.php` :
```
    </body>
</html>
```
En procédant ainsi, on ne se répète pas : si on souhaite modifier l'entête du site, par exemple, il suffira de modifier un seul fichier. À l'inverse, il aurait fallu modifier une multitude de fichiers, dans le cas d'un site statique.

On note l'utilisation de la fonction `require_once`, qui permet de s'assurer que le fichier ne sera inclus qu'une seule fois. Cela peut s'avérer utile, particulièrement dans le cas de scripts longs ou on court le risque d'inclure plusieurs fois le même fichier.

Pour éviter de se répéter, on utilise régulièrement des fonctions et des boucles. Imaginons une galerie de 3 images, dont le chemin de chacune se trouve dans une case du tableau `$img`. Si on souhaite afficher ces images, plusieurs possibilités s'offrent à nous :

Possibilité 1 :
```
<figure>
    <img src="<?php echo $img[0] ?>" alt="" />
</figure>
<figure>
    <img src="<?php echo $img[1] ?>" alt="" />
</figure>
<figure>
    <img src="<?php echo $img[2] ?>" alt="" />
</figure>
```
Possibilité 2 :
```
<?php foreach($img as $curImg) : ?>
<figure>
    <img src="<?php echo $curImg ?>" alt="" />
</figure>
<?php endforeach ?>
```
Dans le deuxième exemple, on 'factorise' le code. On utilisera plutôt la deuxième méthode, étant donné qu'elle est plus courte, et surtout qu'elle respecte le principe du Don't Repeat Yourself.

Les fichiers de fonctions, ainsi que les fichiers de configuration, peuvent alors être inclus dans le fichier `header.php` : Les scripts JS et les fichiers CSS y seront également inclus.

Exemple de fichier header
```
<?php
    require_once __DIR__ . '/../resources/functions.php';
?>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/site.css" type="text/css" media="all">
        <script src="assets/js/script.js"></script>
    </head>
    <body>
```

## Planification du parcours utilisateur

On ne construit pas un site web dynamique de la même manière qu'on rédige une lettre, en rajoutant des paragraphes à la volée. Les informations disponibles sur le site sont susceptibles de varier, en volume comme en nature. Il faut prendre en compte cela dès le début du projet.

Avant de commencer à développer, il est nécessaire de déterminer les fonctionnalités et les informations auxquels aura accès le visiteur. Si le site doit inclure une interaction avec l'utilisateur (formulaires, etc), les différentes étapes doivent être définies avec précision.

Le zoning permet de définir au brouillon les différents rôles de chaque partie, ou section, d'une page web. Par exemple, on choisit de placer une barre de navigation en haut et une secondaire sur la gauche, de placer une galerie dans une colonne sur la droite, etc.

On produit ensuite des maquettes plus précises, qui ne présentent pas les contenus en détail (images, textes), mais donnent leur emplacement précis.

Enfin, on peut construire une sorte de 'story board', ou de scénario, qui déroule une interaction type avec l'utilisateur, chaque étape étant associée à une maquette de la page affichée.

Exemple :

1. Le visiteur arrive sur la page d'accueil -> Page `home`
2. Il clique sur "Ajouter une image à la galerie" et est conduit sur le formulaire d'ajout -> Page `add-image`
3. Il clique sur "Envoyer une image"
4. Il renseigne le titre et la légende
5. Il valide le formulaire.
6. Si le formulaire contient des erreurs, on affiche les messages correspondants -> Page `add-image-errors`
7. Si le formulaire est correct, l'image est téléchargée et l'utilisateur redirigé sur la home page avec un message de confirmation -> Page `home-confirm-upload`

Ces maquettes sont un support indispensable lors d'un travail en équipe particulièrement, mais de manière générale pour avoir une idée précise du résultat qu'on souhaite obtenir lorsqu'on développe une nouvelle fonctionnalité. Elles sont un outil de communication et un support lors du développement.

## Planification de l'organisation des fichiers

Pour chaque scénario, différents fichiers seront mobilisés afin d'obtenir le comportement souhaité. Dans le cas de la modification d'un texte en base de données, par exemple, on affichera une page contenant un formulaire, mais c'est une fonction contenue dans un autre fichier (par exemple, `functions.php`) qui sera appelée. La page de modification fera peut-être appel à un fichier JS pour effectuer une première validation de formulaire.

Il n'y a donc pas de lien de type "Une fonctionnalité = un fichier", mais chaque comportement du site est susceptible de dépendre d'une multitude de fichiers, et chaque fichier pourra être nécessaire à différents scénarios.

Il est donc très important de réfléchir en amont à l'utilisation et au rôle de chacun des fichiers qu'on créera. En général, les fonctions sont placées dans un fichier à part. D'autre part, si on utilise un moteur de templates, on placera les fichiers de templates dans un dossier spécifique.

## Stratégie d'utilisation des paramètres d'URI

Il est possible d'envoyer des paramètres à une page web, comme si on avait envoyé un formulaire avec la méthode `GET`. Par exemple, l'URI `example.com/page.php?id=32` pointe vers le fichier `page.php`, et le serveur enverra au script PHP une variable `$_GET['id']` qui équivaudra à 32.

On peut envoyer plusieurs paramètres à une page : `example.com/image.php?id=12&view=full` remplira le tableau `$_GET` avec une case `id` et une case `view`.

Ces paramètres d'URI permettent de dynamiser une page.

Imaginons une page `view-article.php`, qui a pour rôle d'afficher le contenu d'un article enregistré en base de données. On peut tirer profit du mécanisme des paramètres d'URI :

En accédant à la page, on peut utiliser ces paramètres : `view-article.php?id=4`

**view-article.php**
```
<?php
$idToDisplay = $_GET['id'];
$article = getArticleFromDb($id);       // Récupération de l'article ayant l'ID 4

echo $article['title'];
```
Pour s'assurer que le paramètre a bien été fourni, on peut utiliser cette méthode :
```
<?php
if (isset($_GET['id']) && !empty($_GET['id']) && ctype_digit($_GET['id'])) {
    $idToDisplay = $_GET['id'];
    $article = getArticleFromDb($id);       // Récupération de l'article ayant l'ID 4

    echo $article['title'];
}
```
Note : La fonction ctype_digit vérifie si tous les caractères de la chaîne qui lui est passée sont des chiffres. Tous les paramètres passés dans l'URI sont, de fait, des chaines de caractères. En réalité, le paramètre `?id=45` équivaut à passer la valeur "45", et pas 45.

Il est particulièrement important de s'assurer de l'existence et de la conformité des paramètres qui sont passés à un script PHP, surtout si un de ces paramètres est utilisé lors d'une requête en base de données (comme ici).

## Mysql : Les types de relation

### Relations de type 1:1

Les relations de type 1:1, appelées "one-to-one", permettent d'établir un lien logique entre deux entités, lorsqu'elles l'une et l'autre se concernent uniquement mutuellement. Exemple : on souhaite enregistrer en base de données des utilisateurs, ainsi que les informations les concernant. Il est possible de placer ces données dans deux tables séparées.
```
+----------+
|  Users   |
+----------+
| id       |
| login    |
| password |
| mail     |
+----------+

+-----------------+
|   UserDetails   |
+-----------------+
| id              |
| last_connection |
| recovery_token  |
| next_renewal    |
+-----------------+
```
Pour établir un lien entre elles, on peut ajouter un attribut qui fera office de clé étrangère dans une des tables, et qui ciblera l'autre table par son ID. La nouvelle configuration :
```
+----------+
|  Users   |
+----------+
| id       |
| login    |
| password |
| mail     |
+----------+
      | 1
      |
      |
      |
      | 1
+-----------------+
|   UserDetails   |
+-----------------+
| id              |
| last_connection |
| recovery_token  |
| next_renewal    |
| id_user         |
+-----------------+
```
La clé `UserDetails.id_user` contiendra l'ID d'un utilisateur. Exemple d'enregistrements dans la table `Users` :
```
+----+--------------+----------+--------------------+
| id |    login     | password |        mail        |
+----+--------------+----------+--------------------+
|  1 | CharlieBrown | XXXX     | cbrown@example.com |
|  2 | JohnDoe      | XXXX     | jdoeexample.com    |
+----+--------------+----------+--------------------+
```
On aura une table `UserDetails` qui ressemblera à ceci :
```
+----+-----------------+----------------+--------------+---------+
| id | last_connection | recovery_token | next_renewal | id_user |
+----+-----------------+----------------+--------------+---------+
|  1 | 2016-03-30      | NULL           | 2018-01-01   |       1 |
|  2 | 2016-04-02      | NULL           | 2017-06-01   |       2 |
+----+-----------------+----------------+--------------+---------+
```
Note : Ici, `UserDetails.id_user` et `UserDetails.id` sont identiques, mais ce n'est pas systématique dans les relations 1:1. Les relations de type 1:1 sont les plus simples et sont plutôt rares, car souvent on place tous les attributs dans la même table. Parfois, on doit séparer les données dans deux tables différentes, et c'est ici qu'intervient ce type de relations.

Concrètement, dans le SGBDR, on ajoutera une contrainte d'unicité sur l'attribut `UserDetails.id_user`, car sinon rien n'empêche d'attribuer plusieurs `UserDetails` à une seule entité de `Users` (la relation devant alors 1:n).

### Relations de type 1:n

Les relations de type 1:n sont très courantes, car dans la réalité de nombreuses entités sont liées entre elles de cette manière. Par exemple, un hôtel dispose de plusieurs chambres, et les chambres n'appartiennent évidemment qu'à un seul hôtel, ou encore un dossier a plusieurs fichiers, mais un fichier ne se trouve que dans un seul dossier.

Exemple de relation "one-to-many", entre des factures et des clients :
```
+---------+
| Clients |
+---------+
| id      |
| fname   |
| lname   |
| adress  |
| zip     |
| city    |
+---------+
     | 1
     |
     |
     | 0..n
+-----------+
|   Bills   |
+-----------+
| id        |
| amount    |
| date      |
| file_path |
| id_client |
+-----------+
```
Exemple de contenu de la table `Clients` :
```
+----+--------+---------+-----------------------+----------+----------+
| id | fname  |  lname  |        adress         |   zip    |   city   |
+----+--------+---------+-----------------------+----------+----------+
|  1 | April  | Lang    | 1898 Redbud Drive     | NY 10013 | New York |
|  2 | Walter | Salinas | 966 Round Table Drive | OH 45246 | Glendale |
+----+--------+---------+-----------------------+----------+----------+
```
Contenu de la table `Bills` :
```
+----+---------+------------+------------------------+-----------+
| id | amount  |    date    |       file_path        | id_client |
+----+---------+------------+------------------------+-----------+
|  1 | 452.80  | 2015-01-05 | bill_lang.pdf          |         1 |
|  2 | 1200.50 | 2015-12-29 | bill_lang_renewal2.pdf |         1 |
|  3 | 238.00  | 2016-04-03 | NULL                   |         2 |
+----+---------+------------+------------------------+-----------+
```
On voit ici que deux factures concernent le même client. Il sera possible, à l'aide d'une jointure, de sélectionner toutes les factures d'un client précis.

Une relation one-to-many désigne une relation ou un enregistrement dans une table parent peut potentiellement référencer plusieurs enfants dans une autre table. Dans cette relation, le parent n'a pas nécessairement plusieurs enfants. En revanche, jamais un enfant n'aura plusieurs parents. Dans ce cas, c'est une relation de type many-to-many qui peut être envisagée.

### Relations de type n:n

Dans une relation de type n:n, ou "many-to-many", une entité A contient un enregistrement parent pour lequel il a plusieurs enfants au sein d'une entité B, et vice versa.

Par exemple, un ingrédient peut être lié à plusieurs recettes, et chaque recette est liée à plusieurs ingrédients. Dans le cas de ce type de relation, une troisième table doit être créée afin d'enregistrer les liens entre les deux autres.

Exemple de lien entre une table `Ingredients` et une table `Recipes` :
```
+-------------+
| Ingredients |
+-------------+
| id          |
| name        |
+-------------+
       | 1
       |
       |
       |
       | n
+--------------------+
| IngredientsRecipes |
+--------------------+
| id_recipe          |
| id_ingredient      |
+--------------------+
       | n
       |
       |
       |
       | 1
+---------+
| Recipes |
+---------+
| id      |
| name    |
| text    |
| rating  |
+---------+
```
`IngredientsRecipes.id_recipe` et `IngredientsRecipes.id_ingredient` sont deux clés étrangères pointant respectivement vers des enregistrements de `Recipes` et de `Ingredients`, mais forment également, à eux deux, la clé primaire (dans ce cas appelée composite) de l'entité associative `IngredientsRecipes`. Une clé primaire composite n'est pas nécessairement constituée de clés étrangères, seulement de plusieurs attributs différents, dont certains peuvent être des clés étrangères.

Il est possible d'enregistrer d'autres informations dans la table de liaison ('associative'), relatives au lien entre les deux entités. Par exemple, on pourrait ici enregistrer la quantité, et le type de quantification (grammes, centilitres, cuillères, etc.) de chaque ingrédient. On pourrait aussi ajouter un booléen, qui précisera si l'ingrédient est optionel ou obligatoire. On aurait alors le modèle suivant :
```
+-------------+
| Ingredients |
+-------------+
| id          |
| name        |
+-------------+
       | 1
       |
       |
       |
       | n
+--------------------+
| IngredientsRecipes |
+--------------------+
| id_recipe          |
| id_ingredient      |
| quantity           |
| quantity_type      |
| is_optional        |
+--------------------+
       | n
       |
       |
       |
       | 1
+---------+
| Recipes |
+---------+
| id      |
| name    |
| text    |
| rating  |
+---------+
```
Exemple d'enregistrements dans ces tables :

Table `Ingredients` :
```
+----+--------------------+
| id |        name        |
+----+--------------------+
|  1 | Oeuf               |
|  2 | Lardons            |
|  3 | Pommes de terre    |
|  4 | Ciboulette         |
|  5 | Oignons            |
|  6 | Fromage à raclette |
+----+--------------------+
```
Table `Recipes` :
```
+----+----------------------+-----------------------------------------------------------------------------------------------------------------------------+--------+
| id |         name         |                                                            text                                                             | rating |
+----+----------------------+-----------------------------------------------------------------------------------------------------------------------------+--------+
|  1 | Omelette aux lardons | Pour gagner du temps je fais cuire les pommes de terre dans l'eau, la veille [...]                                          | 4      |
|  2 | Raclette             | La raclette est un plat typique du Valais, en Suisse, obtenu en raclant une demi-meule de fromage fondue à sa surface [...] | 4.2    |
+----+----------------------+-----------------------------------------------------------------------------------------------------------------------------+--------+
```
Table `IngredientsRecipe` :
```
+-----------+---------------+----------+---------------+----------+
| id_recipe | id_ingredient | quantity | quantity_type | optional |
+-----------+---------------+----------+---------------+----------+
|         1 |             1 |        4 | unités        | FALSE    |
|         1 |             2 |      200 | grammes       | FALSE    |
|         1 |             3 |        2 | unités        | FALSE    |
|         1 |             4 |        1 | brindille     | TRUE     |
|         1 |             5 |        1 | petit oignon  | FALSE    |
|         2 |             3 |        4 | unités        | FALSE    |
|         2 |             6 |      200 | grammes       | FALSE    |
+-----------+---------------+----------+---------------+----------+
```
On remarque que chaque recette a plusieurs ingrédients, mais que chaque ingrédient peut se trouver dans plusieurs recettes. Par ailleurs, certains ingrédients sont facultatifs (booléen optional), et dispose d'une quantité et d'un type de quantité.
