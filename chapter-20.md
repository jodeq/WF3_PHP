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

## Les erreurs SQL

## PDO

## Les classes PDO et PDOStatement

## Connexion à la base de données

## Exécuter des requêtes CUD
