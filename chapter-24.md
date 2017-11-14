# Construire un site dynamique, partie 3

## Stratégies de système de filtre/recherche

### Principe

Le mécanisme des paramètres d'URI nous permet de mettre en place une fonctionnalité de recherche. Un formulaire de type `GET` enverra une chaine de caractères recherchée sur la même page, et le paramètre d'URI sera analysé pour effectuer une recherche.

Lorsqu'on effectue une recherche, on récupère des données du serveur, il est donc logique de configurer le formulaire en `GET`. De plus, l'internaute pourra metre en favori la page recherchée, alors qu'en `POST` il n'aurait pas pu.

Pour la chaîne recherchée, on utilisera par exemple un paramètre `s` comme 'search' ou `q` comme 'query', mais ce n'est pas obligatoire.

### Mise en place

Imaginons une page `shops.php`, qui affiche les magasins présents dans la région. On souhaite y intégrer un formulaire de recherche par mot-clé, et un système de filtrage.

#### Recherche
```
<?php

    $shops; // Ce qui deviendra un tableau de magasins
    if(isset($_GET['s']) && !empty($_GET['s'])) // Si une recherche a été effectuée
    {
        $shops = getShopsLike($_GET['s']); // Récupérer les magasins en fonction d'une recherche
    }
    else
    {
        $shops = getAllShops(); // Récupérer tous les magasins
    }
```
Le formulaire de recherche :
```
<form method="GET" action="#">
    <input type="search" placeholder="Nom du magasin" name="search">
    <input type="submit" value="Rechercher">
</form>
```
La fonction `getShopsLike` ressemblera alors à ceci :
```
<?php

    require_once 'connect.php'; // On a maintenant accès à la connexion à la DB, $dbh

    function getShopsLike($search)
    {
        $statement = $db->prepare("SELECT * FROM shops WHERE name LIKE :search");
        $statement->bindParam(':search', '%' . $search . '%');
        return $statement->fetchAll();
    }
```
Dans la fonction `getShopsLike()`, on utilise la méthode `bindParam`, qui ajoute des guillemets autour de la chaîne de caractères automatiquement. Il est donc nécessaire d'ajouter les caractères `%` dans l'argument lors de l'appel à `bindParam`, et pas directement lors de la requête SQL.

### Filtres de recherche

Nous pouvons tirer profit de la possibilité d'envoyer plusieurs paramètres dans l'URL, en ajoutant par exemple des filtres à la recherche. Imaginons une checkbox 'Ouvert le dimanche'. En base de données, un booléen `sunday_opening` définit si le magasin ouvre le dimanche.

Nouveau formulaire de recherche :
```
<form method="GET" action="#">
    <input type="search" placeholder="Nom du magasin" name="search">
    <label><input type="checkbox" name="sunday_opening" value="yes"> Ouvert le dimanche</label>
    <input type="submit" value="Rechercher">
</form>
```
Quand on effectuera une recherche, en ayant sélectionné la checkbox, l'URI ressemblera à ceci : `shops.php?search=cora&sunday_opening=yes`

Il est possible, lors de la réception du formulaire, de prendre en compte ce nouveau critère pour effectuer une recherche plus ciblée :

`shops.php`
```
<?php

    $shops; // Ce qui deviendra un tableau de magasins
    $sundayOpening = null; // Si on demande un magasin ouvert le dimanche

    if(isset($_GET['sunday_opening']) && $_GET['sunday_opening'] == 'yes') // Si la case "Ouvert le dimache" a été cochée
    {
        $sundayOpening = true;
    }

    if(isset($_GET['s']) && !empty($_GET['s'])) // Si une recherche a été effectuée
    {
        $shops = getShopsLike($_GET['s'], $sundayOpening); // Récupérer les magasins en fonction d'une recherche
    }
    else
    {
        $shops = getAllShops($sundayOpening); // Récupérer tous les magasins
    }
```
Notez que la variable `$sundayOpening` restera à NULL si la case n'est pas cochée, et sera passée, soit à la fonction `getShopsLike()`, soit à `getAllShops()`.

La nouvelle fonction `getShopsLike()` accepte maintenant un deuxième paramètre, facultatif :
```
<?php

    require_once 'connect.php'; // On a maintenant accès à la connexion à la DB, $dbh

    function getShopsLike($search, $sundayOpening = false)
    {
        $queryString = 'SELECT * FROM shops WHERE name LIKE :search'; // Cette partie est commune à toutes les requêtes de recherche
        if($sundayOpening === true) // Si on demande seulement les magasins ouverts le dimanche
        {
            $queryString .= ' AND sunday_opening = TRUE'; // On ajoute une clause à la requête
        }

        $statement = $db->prepare($queryString);
        $statement->bindParam(':search', '%' . $search . '%');
        return $statement->fetchAll();
    }
```
Si on recherche la chaine `'mot'`, la fonction `getShopsLike()` produira maintenant deux types de requêtes, selon l'état de la checkbox du formulaire :

Sans filtre :
```
SELECT * FROM shops WHERE name LIKE '%mot%';
```
Avec filtre :
```
SELECT * FROM shops WHERE name LIKE '%mot%' AND sunday_opening = TRUE;
```

## MySQL : fonctions d'agrégation

### Principe des fonctions d'agrégation

Les fonctions d'agrégation sont utiles pour effectuer des opérations statistiques ou mathématiques sur les données enregistrées en base : on pourra par exemple calculer le total, la moyenne, le minimum, le maximum, etc.

Les principales fonctions d'agrégation sont :

* `COUNT()` Calcul du nombre d'enregistrements sur une table, ou sur une colonne
* `AVG()` Calcul de la moyenne
* `MAX()` Récupérer la valeur maximale d'une colonne
* `MIN()` Récupérer la valeur minimale d'une colonne
* `SUM()` Calculer la somme d'un ensemble d'enregistrements

#### Exemples d'utilisation

Soit la table `Products` :
```
+----+-----------+-------+-------+---------------+
| id |   name    | price | stock |     rayon     |
+----+-----------+-------+-------+---------------+
|  1 | Zenron    | 45.50 | 102   | Quincaillerie |
|  2 | Y-lax     | 10.00 | 417   | Quincaillerie |
|  3 | Insailtax | 18.90 | NULL  | Jardinerie    |
|  4 | Physeco   | 10.00 | 612   | Quincaillerie |
|  5 | Zone Plus | 12.90 | 191   | Jardinerie    |
+----+-----------+-------+-------+---------------+
```
#### `COUNT()`

La fonction `COUNT()` permet d'obtenir le nombre d'enregistrements total de la table :
```
SELECT COUNT(*) FROM products; -> retournera 5.
```
Notez l'usage de l'astérisque dans la fonction `COUNT()`. Il permet de cibler l'ensemble des colonnes. MySQL renverra le nombre d'enregistrements différents en fonction de la valeur passée à `COUNT()`.

La requête `SELECT COUNT(stock) FROM products;` -> retournera `4`, étant donné qu'on a un stock à `NULL` (pour les besoins de l'exemple)

Le mot-clé `GROUP BY` permet de rassembler les enregistrement selon une colonne. On l'utilise avec une fonction d'agrégation.

On peut afficher le nombre de produits par rayon :

Requête : `SELECT rayon, COUNT(*) FROM products GROUP BY rayon;`

Résultat :
```
+----------------+----------+
| rayon          | count(*) |
+----------------+----------+
| Jardinerie     |        2 |
| Quincaillierie |        3 |
+----------------+----------+
```

#### `AVG()`

La fonction `AVG()` renvoie la moyenne sur un ensemble d'enregistrements. On lui passe en paramètre le nom de la colonne sur laquelle il faut calculer la moyenne.

Requête : `SELECT AVG(price) FROM products;`

Résultat :
```
+------------+
| AVG(price) |
+------------+
|  19.460000 |
+------------+
```
De même, on peut utiliser `GROUP BY` pour que la fonction d'agrégation soit utilisée sur des sous-ensembles :

Requête : `SELECT rayon, AVG(price) FROM products GROUP BY rayon;`

Résultat :
```
+----------------+------------+
| rayon          | AVG(price) |
+----------------+------------+
| Jardinerie     |  15.900000 |
| Quincaillierie |  21.833333 |
+----------------+------------+
```
#### `MIN()` et `MAX()`

Ces deux fonctions renvoient la valeur minimale et maximale d'une colonne.

Requête : `SELECT MIN(stock) FROM products;` Cette requête nous donnera le plus petit stock de la table `products`.

Résultat :
```
+------------+
| MIN(stock) |
+------------+
|        102 |
+------------+
```
Avec la clause `GROUP BY` : Requête : `SELECT rayon, MIN(stock) FROM products GROUP BY rayon;`

Résultat :
```
+----------------+------------+
| rayon          | MIN(stock) |
+----------------+------------+
| Jardinerie     |        191 |
| Quincaillierie |        102 |
+----------------+------------+
```

#### `SUM()`

Cette fonction renvoie la somme des valeurs d'une colonne numérique.

Requête : `SELECT SUM(stock) FROM products;`

Résultat :
```
+------------+
| SUM(stock) |
+------------+
|       1322 |
+------------+
```
Utilisation avec `GROUP BY` :

Requête : `SELECT rayon, SUM(stock) FROM products GROUP BY rayon;`

Résultat :
```
+----------------+------------+
| rayon          | SUM(stock) |
+----------------+------------+
| Jardinerie     |        191 |
| Quincaillierie |       1131 |
+----------------+------------+
```
Bien sûr, chacune de ces fonctions d'agrégation peut s'utiliser avec des clauses `WHERE`, s'intégrer dans des sous-requêtes, etc. La clause `GROUP BY` se trouvera après la clause `WHERE`.

Cette requête récupère le nombre de produits coutant plus de 10, pour chaque rayon :

Requête :
```
SELECT rayon, COUNT(*) AS "Nombre de produits"
FROM products
WHERE price > 10
GROUP BY rayon;
```
Résultat :
```
+----------------+--------------------+
| rayon          | Nombre de produits |
+----------------+--------------------+
| Jardinerie     |                  2 |
| Quincaillierie |                  1 |
+----------------+--------------------+
```
