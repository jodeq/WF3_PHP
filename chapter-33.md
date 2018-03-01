# Projet en équipe, partie 3

## MYSQL : Les jointures

Une jointure MySQL permet d'agréger des données issues de champs de différentes tables.
Soit un modèle simple comprenant deux tables :
```
 ____________              _____________
| Users      |            | Roles       |
|------------|0..*    0..1|-------------|
| id         |------------| id          |
| name       |            | description |
| role_id    |            |_____________|
| country_id |
|____________|
```
Le contenu de la table `Users` :
```
+----+---------+---------+------------+
| id | name    | role_id | country_id |
+----+---------+---------+------------+
|  1 | Jon     |    NULL |          1 |
|  2 | Stewart |       1 |          1 |
|  3 | Paul    |       2 |          2 |
|  7 | Agnès   |       1 |          2 |
+----+---------+---------+------------+
```
Et celui de la table `Roles` :
```
+----+----------------+
| id | description    |
+----+----------------+
|  1 | Éditeur        |
|  2 | Administrateur |
|  3 | SuperAdmin     |
+----+----------------+
```
Une jointure entre ces deux tables permettra d'afficher, par exemple, la description du rôle de chaque utilisateur.

On distingue deux types de jointures : interne et externe.

### Jointure interne
La jointure interne affiche l'ensemble des lignes ou la condition de jointure est réalisée.
Par exemple, la requête
```
SELECT Users.name, Roles.description
FROM Users
INNER JOIN Roles
ON Users.role_id = Roles.id;
```
Renvoie la sélection suivante :
```
+---------+----------------+
| name    | description    |
+---------+----------------+
| Stewart | Éditeur        |
| Paul    | Administrateur |
| Agnès   | Éditeur        |
+---------+----------------+
```

### Jointure externe
Dans la dernière sélection, Jon n'apparaît pas, vu que la ligne correspondante ne comporte pas d'attribut `role_id`.
Si nous voulons faire apparaître Jon, c'est une jointure de type `LEFT JOIN` qu'il faudrait utiliser (Car la table `Users` est "à gauche", dans la jointure).

De même, le rôle SuperAdmin n'apparaîtra pas, dans le cas d'`INNER JOIN`. Il faudra utiliser la jointure `RIGHT JOIN`.

Si nous voulons à la fois sélectionner les enregistrements de `Users` et ceux de `Roles` qui ne présentent aucune correspondance décrite dans la clause de jointure, on pourra utiliser une `UNION` (Ce type de jointure a pour nom `FULL OUTER JOIN`, mais n'est pas pris en compte nativement par MySQL).

### Jointures multiples
Imaginons que nous souhaitions joindre une table supplémentaire à notre sélection : la table `Countries` dont voici l'affichage :
```
+----+------------+
| id | name       |
+----+------------+
|  1 | États-Unis |
|  2 | France     |
|  3 | Danemark   |
+----+------------+
```
Si nous voulions afficher sur chaque ligne le pays d'origine de chaque utilisateur, nous pourrions ajouter une jointure à notre requête :
```
SELECT Users.name, Roles.description, Countries.name
FROM Users
LEFT JOIN Roles ON Roles.id = Users.role_id
INNER JOIN Countries ON Countries.id = Users.country_id;
```
La sélection renvoyée :
```
+---------+----------------+------------+
| name    | description    | name       |
+---------+----------------+------------+
| Jon     | NULL           | États-Unis |
| Stewart | Éditeur        | États-Unis |
| Paul    | Administrateur | France     |
| Agnès   | Éditeur        | France     |
+---------+----------------+------------+
```
Un bon aide mémoire pour utiliser correctement les jointures peut être trouvé [ici](http://www.codeproject.com/KB/database/Visual_SQL_Joins/Visual_SQL_JOINS_orig.jpg)

### Clause `WHERE`
On peut également utiliser le mot-clé `WHERE` pour joindre des tables entre elles. Il est cependant recommandé de s'habituer à la syntaxe décrite ci-dessus.

Les deux requêtes suivantes produisent le même résultat :
```
# Avec WHERE
SELECT Users.name, Roles.description, Countries.name
FROM Users, Roles, Countries
WHERE
    Users.role_id = Roles.id
    AND Users.country_id = Countries.id;
```
et
```
# Avec INNER JOIN
SELECT Users.name, Roles.description, Countries.name
FROM Users
INNER JOIN Roles ON Roles.id = Users.role_id
INNER JOIN Countries ON Countries.id = Users.country_id;
```

## Mysql : Les sous-requêtes

En MySQL, une sous-requête est une requête placée dans une autre requête. La plupart du temps, on les trouve dans la clause `WHERE`.

Exemple :
```
SELECT Customers.id, Customers.lastname
FROM Customers
WHERE Customers.city IN (
     SELECT Commercials.city
     FROM Commercials
     WHERE Commercials.status = 'PENDING'
);
```
La sous-requête est ici
```
SELECT Commercials.city
FROM Commercials
WHERE Commercials.status = 'PENDING'
```

MySQL commence par obtenir les villes correspondant à la sous-requête, puis exécute une deuxième requête sur la base des résultats.

**Attention** cependant, un traitement supplémentaire en PHP peut parfois être plus performant. La clause `IN` pouvant être très gourmande en ressources serveurs lorsqu'on lui passe une grande quantité de données (ou une sous-requêtes renvoyant beaucoup de résultats).

Autre exemple :
```
SELECT name, price
FROM Products
WHERE Products.price > (
     SELECT AVG(price)
     FROM Products
);
```
Cette dernière requête sélectionne les produits ayant un prix supérieur à la moyenne.

**À noter** : la plupart du temps, la jointure est plus rapide qu'une sous-requête. Mais parfois, la sous-requête est indispensable pour obtenir le résultat souhaité.

## Les expressions rationnelles

Les expressions régulières permettent de vérifier qu'une chaine de caractères répond à certaines règles.

Les expressions régulières peuvent être vues comme des "masques" qui qu'on essaie d'appliquer sur les chaines.

### Fonctions PHP pour les expressions régulières PCRE

La fonction `preg_match` accepte deux paramètres : l'expression à tester et la chaine sur laquelle effectuer la vérification.
En cas de conformité, la fonction renverra `true`, `false` dans les autres cas.

Exemple :
```
$conforme = preg_match('/[A-Z]{5}/', 'ABCDEF');
```
`$conforme` contiendra `1`, car le "pattern" fourni correspond à la chaine.

Le caractère `.` (point) matche n'importe quel autre caractère, sauf le saut de ligne (`\n`).
Si on souhaite sélectionner le point, il faut le protéger : `\.`.

* `preg_match ($pattern, $subject)` renverra `1` ou `0` selon la réussite du test, false en cas d'erreur.
* `preg_match_all ($pattern, $subject, $matches)` renverra le nombre d'occurences du pattern trouvées. `$matches` contiendra, après appel de la fonction, un tableau contenant les valeurs retenues par l'expression.
* `preg_replace ($pattern, $replace, $subject)` remplace `$pattern` par `$replace` dans la chaine `$subject`.

Exemples :
```
$copyDate = "Copyright 2014";
$copyDate = preg_replace("[0-9]{4}", "2016", $copy_date);
```
Le texte deviendra `Copyright 2016`.
```
$pattern = '/(\w+) \d+, (\d+)/';
$replace = '$1 $2 was a good year';
$subject = 'April 15, 2003';
$newText = preg_replace($pattern, $replace, $subject);
```
Le texte deviendra `2003 was a good year`.

### Opérateur de négation

Écrire le caractère `^` en début de classe permet d'inverser la sélection.
La classe matchera les caractères qui ne sont pas précisés dans son contenu.
Par exemple, `[^e]` sélectionnera tous les caractères différents de `e`.
`[^aeiouy]` matchera toutes les consonnes.

### Échappement de caractères

Quand on souhaite sélectionner un caractère spécial (comme `+`), il faut le protéger.
Pour sélectionner `C:\xampp`, il faudra une expression du genre `/C:\\xampp/`

### Classes de caractères

Une "classe" de caractères est comprise entre crochets.
`/gr[ae]y/` permettra par exemple de matcher `grey` et `gray` (mais jamais `graey`).

`[A-Z]` correspond à une "classe" de caractères englobant les lettres de l'alphabet en majuscule.

Si on souhaite sélectionner 3 lettres en majuscule : `[A-Z]{3}`
Le `{3}` correspond au "quantifieur"

Il est possible de cumuler des intervalles dans la même classe :
`[A-Za-z]{5}` correspond à `5` lettres, en majuscules ou en minuscules.

Écrire une classe en majuscules revient à l'inverser.
Par exemple, `/[\W]/` est équivalent à `/[^\w]/` (c'est à dire à `/A-Za-z0-9_/`)

Les caractères unicode accentués peuvent être sélectionnés à l'aide de la classe spéciale `\p{L}`.

### Backtracking

Il est possible de réutiliser un groupe capturé précédemment :
`<([A-Za-z][A-Za-z0-9]*)\b[^>]*>.*?<\/\1>` va sélectionner les balises HTML ouvrantes et fermantes, ainsi que le texte placé entre les deux.
Cette regex capturera ainsi `<em>Bonjour</em>` ou encore `<H3>Titre !</H3>`.
Elle ne capturera pas `<strong>Incohérent</span>`.

### Quantifieurs

* `[a-z]` sélectionne une seule lettre comprise entre `a` et `z`
* `[a-z]+` sélectionne une ou plusieurs lettres comprises entre `a` et `z`
* `[a-z]*` sélectionne de zéro à plusieurs lettres comprises entre `a` et `z`
* `[a-z]?` sélectionne zéro ou une seule lettre comprise entre `a` et `z`

Il est possible de rendre un groupe de caractère facultatifs en l'entourant de parenthèses

Par exemple, `/Nov(ember)?/` matchera `Nov` et `November`.
En revanche, `/Nov[ember]?/` matchera `Nov`, mais aussi `Nove`, `Novm`, `Novb`, etc.

On peut préciser qu'un pattern doit être répété entre X et Y fois :
`\b[\w]{2,8}\b` matchera `Bonjour` (6 caractères), mais pas `BeaucoupTropLong`.

Le quantifieur `{4,}` signifie "4 occurences et plus"
Le quantifieur `{0,4}` signifie "Tout au plus 4 occurences"

### Alternatives

`chat|chien` matchera `J'ai un chat` et `J'ai un chien`.

### Limites de mots

`\b` sélectionne une limite de mot.

* `\bMot\b` matchera `Voici un Mot`
* `\b[\w]*` matchera par exemple `Bonjour tout le monde !`

Il permet notamment d'éviter de sélectionner un caractère accolé à d'autres :
`\b4\b` ne sélectionnera pas `Une feuille A4`, mais `J'ai 4 feuilles`

### Classes raccourcies

Certaines classes "spéciales" permettent de raccourcir l'écriture.
Par exemple, `[A-Za-z0-9_]{5}` sélectionne `5` caractères qui doivent obligatoirement être

* Des lettres (majuscules ou minuscules)
* Des chiffres
* Ou un underscore (`_`)
Cette classe peut être abrégée par `[\w]{5}` (`'w'` pour `'word'`).

D'autres raccourcis existent, tels que

* `\d` pour `[0-9]` (`'d'` pour `'décimal'`)
* `\s` pour `[ \t\r\n\f]` (`(espace)`, `\t` pour "tabulation", `\r` pour "Retour chariot",
 `\n` pour "New line", `\f` pour "Form feed")

### Début et fin de chaine

Deux caractères spéciaux existent pour indiquer le début ou la fin d'une chaine :
`^` marque le début, `$` marque la fin.

L'expression `/^abc$/` validera uniquement `abc`, alors que `/abc/` aurait également validé `abcd` ou même `zabcd`.

### Ressources
Certains sites permettent de tester "en direct" une expression régulière, comme par exemple https://regex101.com/
