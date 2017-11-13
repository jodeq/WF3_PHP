# Construire un site dynamique - partie 2

## composer

### Définition

[Composer](https://getcomposer.org/) est un gestionnaire de dépendances, écrit en PHP.

Cela permet de gérer les librairies, frameworks, etc. que l'on souhaite utiliser sans avoir à les télécharger manuellement, à les mettre à jour facilement, et à contrôler plus simplement la version à utiliser. Cela permet également de faire en sorte que tout le monde puisse avoir la même version des librairies en même temps sur un même projet.

### Structure

* La définition du projet et de ses dépendances se trouvent dans un fichier `composer.json`
* Une fois les dépendances installées, un fichier `composer.lock` contient les informations sur les dépendances installées et leur version.

Ces deux fichiers `composer.json` et `composer.lock` doivent systématiquement être versionnés sur votre projet.

### Les dépendances

Les projets disponibles sont répertoriées sur le site [Packagist](https://packagist.org/explore/) qui sert de base centrale à tous les projets publiques.

On peut soit les définir à la main dans le `composer.json`, sous le noeud require :
```
{
    "require": {
        "acme/foo": "1.0.*"
    }
}
```
Il est également possible de le faire en ligne de commande. Ceci mettra automatiquement à jour le `composer.json`
```
php composer require acme/foo:1.0.*
```
### Installer les dépendances spécifiées

L'installation des dépendances se fait via une commande spécifique:
```
php composer install
```
Celle-ci se comporte de deux façons selon le contexte:

* Si aucun fichier `composer.lock` n'est présent, installe les dépendances listées dans le `composer.json` dont la version remplie les critères définis. Un fichier `composer.lock` sera alors créé.
* Si un fichier `composer.lock` existe, installe les dépendances listées dans ce fichier à la version spécifiquement définie

Par défaut, les dépendances sont installées dans un répertoire `vendor` au même niveau que le `composer.json`.

### Mise à jour des dépendances

Admettons que vous ayez spécifié `"acme/foo": "1.0.*"` dans votre `composer.json`, et c'est la version 1.0.2 qui est installée.
Deux nouvelles versions sont sorties, les versions 1.0.3 et 2.0.0.

Vous souhaitez alors mettre à jour votre dépendance en suivant le critère de version défini, il suffit alors d'exécuter la commande:
```
php composer update acme/foo
```
Composer ira alors chercher la version la plus récente remplissant le critère de version 1.0.*, ici il s'agit donc de la version 1.0.3.
Si vous souhaitez mettre à jour en 2.0, il vous faut mettre à jour le critère de version de votre fichier `composer.json`, puis lancer la commande de mise à jour.
Si vous ne spécifiez pas de dépendance, Composer les mettra toutes à jour, ainsi que le fichier `composer.lock`.

### Utiliser les dépendances

Pour utiliser les dépendances, il vous suffit d'inclure l'autoload généré par composer, de la façon suivante:
```
require __DIR__ . '/vendor/autoload.php';
```

## Stratégies de système de pagination

### `LIMIT` et `OFFSET`

Pour éviter d'envoyer au client un trop grand nombre d'informations d'un coup, et ralentir la navigation, on utilise le mécanisme de la pagination. Par exemple, au lieu d'afficher 400 résultats, on affichera plutôt 10 pages de 40 éléments.

MySQL permet de sélectionner une fraction d'un ensemble de résultats. Cette sélection de plage s'opère ainsi :
```
SELECT id, lastname, firstname
FROM Clients
LIMIT 40;
```
Les clauses `LIMIT` et `OFFSET` fonctionnent sur n'importe quelle requête `SELECT`.

`LIMIT 40` placé en fin de requête signifie qu'on ne renverra que les 40 premiers résultats du rowset.

Il est possible de préciser un offset de départ :

`LIMIT 40 OFFSET 10` signifie qu'on ne va renvoyer que les résultats 41 à 61. `OFFSET 10` : on commence à partir du 11ème enregistrement.

Il est alors possible d'utiliser ce mécanisme afin de créer un système de pagination.

Si on souhaite des pages de 20 éléments, la page 1 correspondra à `LIMIT 20` (enregistrements 1 à 20), la page 2 à `LIMIT 20 OFFSET 20` (enregistrements 21 à 41), la page 3 à `LIMIT 20 OFFSET 40` (enregistrements 41 à 61), etc.

### Système de pagination

Exemple simpliste de système de pagination

Soit une page affichant la liste des produits disponibles à l'achat : `view-products.php`.

Faisons en sorte que ce script n'affiche qu'une partie des produits, et qu'il permettre de naviguer de page en page. Les pages seront composées chacune de 10 enregistrements.

Nous allons ajouter un paramètre au script : `view-products.php?page=1`. Le paramètre page permettra d'afficher seulement la page que l'on souhaite visualiser.

On imagine que la fonction `getProductsFromDb()` accepte deux paramètres : la limite et l'offset de la requête de sélection de tous les produits, et renvoie un tableau de produits.

`view-products.php`
```
<?php

    $page; // Le numéro de la page que nous souhaitons visualiser
    if (isset($_GET['page']) && !empty($_GET['page']) && ctype_digit($_GET['page'])) // On vérifie si la page est bien un nombre
    {
        $page = $_GET['page'];
    }
    else // Si le paramètre n'est pas spécifié ou n'est pas un nombre valide
    {
        $page = 1;
    }

    // Maintenant, nous avons le numéro de page. Nous pouvons en déduire les enregistrements à afficher :

    $offset = ($page - 1) * 10;   // Si on est à la page 1, (1-1)*10 = OFFSET 0, si on est à la page 2, (2-1)*10 = OFFSET 10, etc.

    $products = getProductsFromDb(10, $offset);

    // Affichage des produits
    foreach($products as $product) : ?>
    <article>
        <header><?php echo $product['name'] ?></header>
        <div>
            <?php echo $product['description'] ?>
        </div>
    </article>
    <?php endforeach;
```

Nous pouvons maintenant accéder au script, en utilisant un paramètre d'URI pour choisir la page à afficher : `script.php?page=1`, `script.php?page=2`, etc.

Il est possible d'afficher les liens vers les pages précédentes et suivantes, en prenant quelques précautions (voir les conditionnelles à la fin) : Note : On imagine la fonction `getNbProductsFromDb()`, qui renvoie le nombre (COUNT(*)) de produits enregistrés en base de données.

`view-products.php`
```
<?php

    $nbProducts = getNbProductsFromDb();
    $maxPage = ceil($nbProducts / 20);

    // La fonction ceil() arrondit à l'entier supérieur.
    // Exemple : On a 352 produits en base de données. 352 / 20 par page = 17.6 pages, soit 18 pages.

    ...

        </div>
    </article>
    <?php endforeach;

    if ($page > 1 ) // Seulement si on est sur la page 2 ou plus, afficher un bouton "Précédent"
    {
        echo '<a href="?page=<?php echo ($page - 1) ?>">Précédent </a>';
    }

    if ($page < $maxPage) // Seulement si on est pas sur la dernière page, afficher un bouton "Suivant"
    {
        echo '<a href="?page=<?php echo ($page + 1) ?>">Suivant</a>';
    }
```
Il ne reste plus qu'à prendre en compte le cas ou quelqu'un essaierait de forcer un numéro de page incorrect (par exemple, `view-articles.php?page=9999` ou encore `view-articles.php?page=0`). On corrige pour cela le début du script (voir la conditionnelle, qui est devenue plus longue). On ajoute également un message pour situer l'utilisateur dans sa navigation.

`view-products.php`
```
<?php

    $nbProducts = getNbProductsFromDb();
    $maxPage = ceil($nbProducts / 20);

    // La fonction ceil() arrondit à l'entier supérieur.
    // Exemple : On a 352 produits en base de données. 352 / 20 par page = 17.6 pages, soit 18 pages.

    $page; // Le numéro de la page que nous souhaitons visualiser
    if (isset($_GET['page']) && !empty($_GET['page']) && ctype_digit($_GET['page']) && $page > 0 && $page <= $maxPage) // On vérifie si la page est bien un nombre compris entre 1 et $maxPage
    {
        $page = $_GET['page'];
    }
    else // Si le paramètre n'est pas spécifié ou n'est pas un nombre valide
    {
        $page = 1;
    }

    // Maintenant, nous avons le numéro de page. Nous pouvons en déduire les enregistrements à afficher :
    $offset = ($page - 1) * 10;   // Si on est à la page 1, (1-1)*10 = OFFSET 0, si on est à la page 2, (2-1)*10 = OFFSET 10, etc.

    $products = getProductsFromDb(10, $offset);

    // Affichage des produits
    foreach($products as $product) : ?>
    <article>
        <header><?php echo $product['name'] ?></header>
        <div>
            <?php echo $product['description'] ?>
        </div>
    </article>
    <?php endforeach;

    // Nous pouvons ajouter un message, qui permet à l'utilisateur de se situer dans la navigation :
    // $offset + 1 correspond à l'indice du premier article affiché
    // $offset + 11 correspond à l'indice du dernier article affiché
    // On souhaite afficher un message du genre "Articles 21 à 31 (sur 152)"
    ?>
    <p>
        Articles <?php echo ($offset + 1) ?> à <?php echo ($offset + 11) ?> (sur <?php echo $nbProducts ?>)
    </p>
    <?php

    if ($page > 1 ) // Seulement si on est sur la page 2 ou plus, afficher un bouton "Précédent"
    {
        echo '<a href="?page=<?php echo ($page - 1) ?>">Précédent </a>';
    }

    if ($page < $maxPage) // Seulement si on est pas sur la dernière page, afficher un bouton "Suivant"
    {
        echo '<a href="?page=<?php echo ($page + 1) ?>">Suivant</a>';
    }

    // On aurait pu afficher une suite de liens vers chacune des pages, en bouclant de 1 à $maxPage.
```
