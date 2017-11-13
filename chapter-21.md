# PHP et Mysql avec PDO

## Les Injecions SQL

Une injection SQL consiste à profiter d'une faille dans les paramètres transmis à une requête SQL, pour lui faire exécuter un code SQL malicieux.

La faille la plus répandue est l'utilisation d'un paramètre d'url utilisé dans une requête non préparée.

Exemple :

`page.php`
```
$id = $_GET['id'];

$query = $db->query('SELECT * FROM user WHERE id = '.$id);
$user = $query->fetch();
```
Dans cet exemple, le paramètre d'url id est récupéré "brut" et transmis directement à la requête avec une concaténation.

Si on appelle la page en transmettant le paramètre suivant : `page.php?id=0; DROP TABLE user`, la requête suivante sera exécutée :
```
SELECT * FROM user WHERE id = 0; DROP TABLE user;
```
Ce qui provoquera la suppression de la table "user".

### Protections

Dans cet exemple, si le paramètre id doit être un nombre, une protection consisterait à forcer le paramètre en nombre au moment de le récupérer.

Exemple :
```
$id = intval($_GET['id']);
```
Toute chaîne de caractère sera convertie en 0.

Une protection plus efficace consiste à préparer les requêtes et à transmettre les valeurs des paramètres avec la méthode `bindValue()` ou `bindParam()`.

Exemple :

`search.php`
```
$search = $_GET['q'];

$query = $db->prepare('SELECT * FROM articles WHERE title LIKE :search');
$query->bindValue(':search', $search, PDO::PARAM_STR);
$search_results = $query->fetchAll();
```
Dans cet exemple, le paramètre `search`, défini en tant que chaîne de caractère, sera automatiquement entouré de guillemets. Tout code SQL sera donc ignoré car considéré comme une valeur.

Exemple, si on appelle la page en transmettant le paramètre suivant : `search.php?q=test; DROP TABLE articles`, la requête suivante sera exécutée :
```
SELECT * FROM articles WHERE title LIKE "test; DROP TABLE articles";
```
La requête ne retournera probablement pas de résultat, mais aucune action malicieuse ne sera effectuée.

## Les attaques XSS

Les attaques XSS (cross-site scripting) consistent à profiter d'une faille sur une page web, afin d'y injecter du code Javascript qui s'exécutera à chaque affichage de la page.

Le cas le plus courant survient lors de la transmission de données de formulaire, données qui seront insérées en base de données, puis affichées sur la page d'accueil d'un site.

Exemple : `form.php`
```
<?php
$pseudo = $_POST['pseudo'];

$query = $db->prepare('INSERT INTO user (pseudo) VALUES (:pseudo)');
$query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
$query->execute();
?>
<form method="POST">
    <input name="pseudo" type="text" />
    <input type="submit" value="OK" />
</form>
```
`index.php`
```
<?php
$query = $db->query('SELECT pseudo FROM user');
$users = $query->fetchAll();

foreach($users as $user) {
    echo $user['pseudo'].'<br>';
}
?>
```
Sur la page `form.php`, si l'on saisi comme pseudo `<script>location.href = 'http://www.monsite.com';</script>`, tous les visiteurs de la page `index.php` exécuteront le code Javascript et seront redirigés vers le site `http://www.monsite.com`.

### Protections

Pour se prémunir des failles XSS, il existe des fonctions PHP qui permettent de supprimer/nettoyer le code HTML présent dans les chaînes de caractère.

Exemple : `form.php`
```
$pseudo = strip_tags($_POST['pseudo']); // Supprime toutes les balises HTML
$pseudo = htmlspecialchars($_POST['pseudo']); // Remplace tous les caractères HTML par leur équivalent en HTML entities
```
Consulter la documentation PHP pour plus d'informations sur les fonctions [`strip_tags`](http://php.net/manual/fr/function.strip-tags.php) et [`htmlspecialchars`](http://php.net/manual/fr/function.htmlspecialchars.php).
