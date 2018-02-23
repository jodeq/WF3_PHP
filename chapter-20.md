# SQL, et introduction à PDO

## CRUD de base en SQL

* **CRUD** : Create, Read, Update, Delete, les quatre opérations de base pour le stockage et la manipulation d'informations dans une base de données.

* **Create** Insérer des nouveaux enregistrements (écriture)
* **Read** Récupérer des enregistrements (lecture)
* **Update** Mettre à jour des enregistrements (écriture)
* **Delete** Supprimer des enregistrements (écriture)

**Create**
```
INSERT INTO user (firstname, lastname, email) VALUES ('John', 'Doe', 'john.doe@mail.com');
-- Syntaxe alternative
INSERT INTO user SET firstname = 'John', lastname = 'Doe', email = 'john.doe@mail.com';
-- La syntaxe alternative ne permet pas d'insérer plusieurs séries de valeurs en une seule requête, contrairement à la syntaxe de base.

Exemple :

INSERT INTO user (firstname, lastname, email) VALUES
('John', 'Doe', 'john.doe@mail.com'),
('Bob', 'Arctor', 'bob.arctor@mail.com');
```

**Read**
```
SELECT firstname, lastname FROM user;
```

**Update**
```
UPDATE user SET firstname = 'John', lastname = 'Doe' WHERE id = 123;
```

**Delete**
```
DELETE FROM user WHERE email = '';
```

## Clauses et fonctions SQL de base

### Clauses SQL de base

**SELECT / FROM**

La clause `SELECT` précise la liste des colonnes à remonter ou * pour remonter toutes les colonnes de la table.
La clause `FROM` précise la table sur laquelle doit être effectuée la sélection.
On peut utiliser des fonctions SQL dans la clause `SELECT` (c.f. fonctions SQL).

Exemple :
```
SELECT colonne1, colonne2 FROM ma_table;
SELECT * FROM ma_table;
SELECT COUNT(id) FROM ma_table;
```
Les colonnes peuvent être remontées avec des noms personnalisés Exemple :
```
SELECT colonne1, colonne2 FROM ma_table;
SELECT * FROM ma_table;
```

**WHERE**

Réservée aux requêtes `SELECT`, `UPDATE` et `DELETE`, permet de filtrer la sélection ou les lignes à mettre à jour ou à supprimer.

Exemple :
```
SELECT firstname, lastname
  FROM user
  WHERE age >= 18; -- Remonte le prénom et le nom de tous les utilisateurs de 18 ans ou plus

UPDATE contact
  SET newsletter = 0
  WHERE email = ''; -- Mets à jour le champ newsletter de tous les contacts avec un email vide

DELETE FROM post
  WHERE archive = 1; -- Supprime tous les posts dont la colonne archive vaut 1
```
On peut combiner plusieurs clauses `WHERE` en les séparant par l'opérateur `AND` et/ou `OR`. La gestion des combinatoires et des priorités est complétées avec des parenthèses.

Exemple :
```
SELECT firstname, lastname, email
  FROM user
  WHERE email != ''      -- Remonte tous les utilisateurs dont l'email n'est pas vide
    AND newsletter = 1   -- qui ont accepté de recevoir la newsletter
    AND (country = 'FR' OR country = 'BE' OR country = 'CH');
                         -- et qui résident en France OU en Belgique OU en Suisse
```

**ORDER BY**

Réservé aux requêtes `SELECT`, permet de trier la liste des résultats de la requête, en ordre croissant (`ASC`) ou décroissant (`DESC`).
Par défaut si on ne précise pas l'ordre de tri, c'est un tri croissant qui est effectué. On peut combiner plusieurs clauses de tri en les séparant avec des virgules.

Exemple :
```
SELECT * FROM movies ORDER BY release_date DESC; // Remonte tous les films triés par date de sortie décroissante
SELECT * FROM movies ORDER BY RAND(); // Remonte tous les films dans un ordre pseudo aléatoire
SELECT * FROM movies ORDER BY lastname ASC, firstname ASC; // Remonte tous les films triés par ordre alphabétique sur le nom de famille, et à nom égal par ordre alphabétique croissant sur le prénom
```

**LIMIT**

Réservé aux requêtes `SELECT`, permet de restreindre la sélection en précisant un nombre de ligne, et éventuellement un point de départ, permettant entre autres de gérer une pagination.

Exemple :
```
SELECT * FROM movies ORDER BY RAND() LIMIT 3; // Remonte 3 films aléatoires
SELECT * FROM movies LIMIT 50, 10; // Remonte 50 films à partir de la ligne 10
```

**INSERT INTO**

Permet d'insérer une ligne dans une table en précisant les colonnes à remplir avec leur valeur respective.

Exemple :
```
INSERT INTO user (firstname, lastname, email) VALUES ('John', 'Doe', 'john.doe@mail.com'); // Insère une ligne dans la table user
INSERT INTO user SET firstname = 'John', lastname = 'Doe', email = 'john.doe@mail.com'; // Syntaxe alternative, même résultat que la requête précédente
```

**UPDATE**

Permet de modifier une ou plusieurs ligne(s) existante(s) d'une table.

Exemple :
```
UPDATE contact SET newsletter = 1, update_date = NOW() WHERE id = 42; // Modifie la colonne newsletter et update_date de l'utilisateur 42
UPDATE movies SET rank = rank + 1 WHERE id = 123; // Modifie le champ rank en l'incrémentant de 1 pour l'identifiant 123
UPDATE user SET newsletter = 0 WHERE email = ''; // Modifie la colonne newsletter pour tous les user avec une colonne email vide
```
ATTENTION : Une requête `UPDATE` sans clause `WHERE` affectera toutes les lignes de la table

**DELETE**

Permet de supprimer une ou plusieurs ligne(s) depuis une table.

Exemple :
```
DELETE FROM user WHERE id = 42; // Supprime la ligne de la table user dont l'identifiant est 42
DELETE FROM mail WHERE deleted = 1; // Supprime tous les lignes de la table mail dont la colonne deleted vaut 1
```

ATTENTION : Une requête `DELETE` sans clause `WHERE` affectera toutes les lignes de la table

### Fonctions SQL de base

`COUNT`
Permet de compter le nombre de lignes remontées par une requête.
```
SELECT COUNT(id) FROM user; // Remonte le nombre de lignes de la table user
```

`MIN/MAX` Permet de retourner la plus petite/grande valeur d'une colonne.
```
SELECT MIN(year) as min_year, MAX(year) as max_year FROM movies; // Renvoie l'année du film le plus ancien et l'année du film le plus récent
```
`AVG`

Calcul et renvoit la moyenne de la colonne scores pour toutes les lignes
```
SELECT AVG(scores) FROM nom_de_la_table;
```
`SUM` Permet de faire la somme de tous les valeurs d'une colonne.
```
SELECT SUM(scores) FROM games; -- Calcul et renvoit la somme des valeurs de la colonne scores
SELECT SUM(price) FROM products; -- Même chose avec des prix de produits
```
`NOW` Renvoie la date et l'heure courante
```
UDPATE user SET last_connexion = NOW(); // Modifie la date de dernière connexion au format "Y-m-d H:i:s"
```

## Les erreurs SQL

Différents types d'erreurs peuvent survenir lors de la connexion à la base de données MySQL, et durant l'exécution des requêtes SQL.

**Les erreurs les plus fréquentes à la connexion**

* [2002] php_network_getaddresses: getaddrinfo failed: Hôte inconnu

* [2002] Une tentative de connexion a échoué car le parti connecté n'a pas répondu convenablement au-delà d'une certaine durée ou une connexion établie a échoué car l'hôte de connexion n'a pas répondu.

    Le paramètre host du DSN semble incorrect ou le serveur ne répond pas. Le paramètre host doit respecter les formats suivants : `127.0.0.1`, `localhost`, `12.34.56.78`, `http://www.domain.com`.

* [1045] Access denied for user 'root'@'localhost' (using password: YES)

    L'utilisateur root n'est pas autorisé à se connecter à la base de données, ou le mot de passe est incorrect.

* [1049] Unknown database 'db_name'

    Le nom de la base de données est incorrect ou l'utilisateur connecté n'a pas les droits de lecture sur cette base de données.

**Les erreurs SQL les plus fréquentes**

* [42000] Syntax error or access violation [1064] You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'SELECT FROM'...

      La requête comporte une erreur de syntaxe, il faut vérifier les points suivants :
        -Le nom et l'ordre des instructions est correct
        -Il ne manque aucune virgule, parenthèse ou opérateur (=, !=, ...)
        -Aucun mot clé réservé à SQL n'est utilisé comme nom de table ou de colonne (select, from, match, against...etc) sans être échappé avec des `

* [42S02] Base table or view not found [1146] Table 'db_name.table2' doesn't exist

    La table sur laquelle porte la requête est incorrect ou n'existe pas, il faut vérifier sa présence et son nom exact dans la base de données

* [42S22] Column not found: 1054 Unknown column 'tittle' in 'field list'

    Le nom d'une colonne utilisée dans la requête est incorrect ou n'existe pas, il faut vérifier sa présence et son nom exact dans la base de données

**Liens utiles**

* [Liste complète des erreurs soulevées par MySQL](https://dev.mysql.com/doc/refman/5.5/en/error-messages-server.html)
* [La gestion des erreurs avec PDO](http://php.net/manual/fr/pdo.error-handling.php)
* [Stack Overflow](http://stackoverflow.com/)

## PDO

L'extension PHP Data Objects (PDO) est une interface permettant d'interroger différents types de base de données. Elle est généralement utilisée en interface pour MySQL.

Un des nombreux avantages de PDO est qu'on peut interroger les différents types de base de données avec les mêmes méthodes.

PDO prend la forme d'une classe qu'on instancie pour effectuer une connexion vers la base de données (c.f. Connexion à la base de données).
Elle permet ensuite d'exécuter des requêtes en utilisant cette connexion.

PDO permet en outre de prévenir des injections SQL (c.f. injections SQL) en utilisant les requêtes préparées (c.f. Requêtes préparées).

L'utilisation des mécanismes avancés de PDO permettent également des gains de performance.

## Connexion à la base de données

La connexion à la base de données avec PDO s'effectue en déclarant une nouvelle instance de la classe PDO, et en lui passant les paramètres suivants en argument :

**DSN** Le DSN (Data Source Name) est une chaîne de caractère composée de :
 * Type de base de données
 * Chemin d'accès à cette base de données :
   + host qui désigne le domaine sur lequel la base de données est hébergée (localhost sur un serveur local type XAMPP)
   + db_name qui désigne le nom de la base de données à laquelle on souhaite se connecter.

**Identifiants de connexion** Les identifiants de connexion sont définis dans la configuration de MySQL et composés d'un nom d'utilisateur et d'un mot de passe. L'utilisateur désigné doit disposer des droits d'accès en lecture et/ou écriture pour pouvoir effectuer des opérations sur la base de données.

**Options supplémentaires** D'autres options peuvent également être définies pour modifier la configuration, telles que l'encodage des caractères ou la gestion des erreurs.

Exemple : Connexion simple
```
$connexion = new PDO('mysql:host=localhost;dbname=ma_bdd', 'login', 'password');
```
Connexion avancée
```
define('HOST', 'localhost'); // Domaine ou IP du serveur ou est située la base de données
define('USER', 'root'); // Nom d'utilisateur autorisé à se connecter à la base
define('PASS', ''); // Mot de passe de connexion à la base
define('DB', 'db_name'); // Base de données sur laquelle on va faire les requêtes

// Tableau d'options supplémentaires pour la connexion
$db_options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", // On force l'encodage en utf8
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // On récupère tous les résultats en tableau associatif
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING // On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
);

// On crée la connexion à la base de données
$db = new PDO('mysql:host='.HOST.';dbname='.DB, USER, PASS, $db_options);
```
La classe PDO soulève des exceptions en cas d'erreur de connexion, ces exceptions peuvent être gérées dans un bloc try/catch

Exemple :
```
try {
  $db = new PDO(...);
} catch (Exception $e) {
  error_log('['.$e->getCode().'] '.$e->getMessage(), 3, 'logs/mysql-errors.log');
}
```
## Les classes PDO et PDOStatement

**La classe PDO**

Représente la connexion entre PHP et la base de données.

Doit être instanciée avec un DSN valide pour être utilisée (c.f. "Connexion à la base de données").

Exemple :
```
$db = new PDO('mysql:host=localhost;dbname=ma_bdd', 'login', 'password');
```
Voici quelques méthodes de la classe PDO, couramment utilisées :

**query**

Exécute une requête et retourne un objet PDOStatement.

Exemple :
```
$query = $db->query('SELECT * FROM ma_table');
```

**prepare**

Prépare une requête à l'exécution et retourne un objet PDOStatement.

Exemple :
```
$query = $db->prepare('SELECT * FROM ma_table WHERE id = :id');
```
Préparer une requête permet de lui transmettre des paramètres, grâce aux méthodes bindValue() et bindParam() (c.f. "La classe PDOStatement"). Cela permet notamment d'éviter les injections SQL et d'améliorer les performances.

**lastInsertId**

Retourne le dernier identifiant auto incrémenté suite à une requête INSERT.

Exemple :
```
$query = $db->prepare('INSERT INTO ma_table SET col = :value');
$query->bindValue(':value', 'test', PDO::PARAM_STR);
$query->execute();
$insert_id = $db->lastInsertId();
```
D'autres méthodes sont disponibles telles que errorInfo(), errorCode(), ...etc
Consulter la documentation de PHP pour obtenir la liste complète : http://php.net/manual/fr/class.pdo.php

**La classe PDOStatement**

Représente une requête préparée et, une fois exécutée, le jeu de résultats associé.

Voici quelques méthodes de la classe PDO, couramment utilisées :

**fetch / fetchAll**

Les méthodes fetch() et fetchAll() permettent de récupérer les résultats d'une requête SELECT préalablement exécutée.

Exemple :
```
// Sans prepare
$query = $db->query('SELECT * FROM ma_table');
$result = $query->fetch(); // Renvoie une seule ligne de résultat
$results = $query->fetchAll(); // Renvoie toutes les lignes de résultat

// Avec prepare
$query = $db->prepare('SELECT * FROM ma_table');
$query->execute();
$result = $query->fetch(); // Renvoie une seule ligne de résultat
$results = $query->fetchAll(); // Renvoie toutes les lignes de résultat
```

**bindValue**

La méthode bindValue() permet de transmettre la valeur des paramètres d'une requête préparée. (c.f. méthode prepare() de "La classe PDO"). Elle prend 3 arguments :

* Le nom du paramètre sous la forme :nom;
* La valeur du paramètre qui peut être une chaîne ou un nombre;
* Le type du paramètre (PDO::PARAM_STR pour les chaînes, PDO::PARAM_INT pour les nombres);

Exemple :
```
$query = $db->prepare('SELECT * FROM ma_table WHERE id = :id');
$query->bindValue(':id', 42, PDO::PARAM_INT);
$query->execute();
$result = $query->fetch();
```

**bindParam**

La méthode bindParam() est similaire à la méthode bindValue(), sauf qu'au lieu de lui transmettre une valeur pour un paramètre, on lie le paramètre avec un nom de variable, variable qui sera définie plus tard.

L'intérêt de cette méthode réside surtout dans sa capacité à exécuter plusieurs fois la même requête, avec des valeurs différentes, sans refaire la phase de préparation et de transmission de paramètre, ce qui améliore les performances sur un gros volume de requêtes.

Exemple :
```
$query = $db->prepare('INSERT INTO ma_table SET param = :param');
$query->bindParam(':param', $param, PDO::PARAM_INT);

for($i = 0; $i < 10; $i++) {
    $param = $i;
    $query->execute();
}   
```

**execute**

Permet d'exécuter une requête préparée. La requête n'est réellement jouée, qu'au moment de l'appel à execute().

CONSEIL : Il est fréquent d'oublier l'appel à cette fonction et de s'étonner de pas obtenir de résultats malgré une requête valide...

Exemple :
```
$query = $db->prepare('SELECT * FROM ma_table');
$query->execute();
```
D'autres méthodes sont disponibles telles que errorInfo(), errorCode(), ...etc
Consulter la documentation de PHP pour obtenir la liste complète : http://php.net/manual/fr/class.pdostatement.php

## Exécuter des requêtes CUD

Il existe 3 opérations permettant d'ajouter / modifier / supprimer des enregistrements sur une table de base de données.

Il est recommandé de préparer ces requêtes et de transmettre les valeurs des paramètres avec les méthodes bindValue/bindParam.
Cela permet notamment d'éviter les injections SQL, et d'améliorer les performances.

**Create**

Insérer des nouveaux enregistrements.

Exemple :
```
$query = $db->prepare('INSERT INTO ma_table SET col1 = :value1, col2 = :value2');
$query->bindValue(':value1', 123, PDO::PARAM_INT);
$query->bindValue(':value2', 'test', PDO::PARAM_STR);
$query->execute();
$insert_id = $db->lastInsertId(); // Retourne l'identifiant inséré par la requête (nécessite une clé primaire en AUTO_INCREMENT)
```

**Update**

Modifier des enregistrements.

Exemple :
```
$query = $db->prepare('UPDATE ma_table SET col = :value WHERE id = :id');
$query->bindValue(':value', 'test', PDO::PARAM_STR);
$query->bindValue(':id', 123, PDO::PARAM_INT);
$query->execute();
$affected_rows = $query->rowCount(); // Retourne le nombre de lignes affectées par la requête
```

**Delete**

Supprimer des enregistrements.

Exemple :
```
$query = $db->prepare('DELETE FROM ma_table WHERE id = :id');
$query->bindValue(':id', 123, PDO::PARAM_INT);
$query->execute();
$affected_rows = $query->rowCount(); // Retourne le nombre de lignes affectées par la requête
```
