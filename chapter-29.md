# Authentification et autorisations - partie 1

## Inscription de l'utilisateur

Un système de gestion de membre devient nécessaire lorsque vous voulez ajouter une dimension communautaire à votre site web.

Le formulaire d'inscription sera donc le premier point d'accès à cette dimension pour le futur membre de votre communauté.

Le but de ce formulaire sera de récolter les informations du visiteur afin de les stocker en base de données pour qu'il puisse s'identifier via une session dans l'avenir.

### Données demandées

Avant même de traiter l'inscription, il faut réaliser le formulaire d'inscription et préparer la base à enregistrer ces données.
Voici les données qu'il est souvent recommandé d'avoir pour un utilisateur:

* Email: Cela permet d'identifier l'utilisateur de manière unique, mais aussi de lui renvoyer un lien de re-génération de mot de passe en cas d'oubli
* Password: Le mot de passe de l'utilisateur, qui ne doit jamais apparaître en clair dans la base de donnée. Celui-ci ne doit jamais pouvoir être connu (même par une fonction dans le code).
* Date d'inscription: Ce n'est pas indispensable, mais c'est souvent utile.

Il convient également de rendre le champ Email unique dans la base de donnée, pour éviter tout risque d'insertion en double d'un même mail.

### Traitement

Avant d'enregistrer l'utilisateur en base de données, il convient d'effectuer certaines manipulations sur les données fournies par l'utilisateur propre à son inscription. Même si certaines peuvent être faites côté client (validation HTML5), il est impératif de les faire également côté serveur car elles peuvent être facilement contournées.

* S'assurer que les champs sont bien renseignés, et qu'ils ne sont pas vides après avoir supprimé les espaces superflus en début et fin de chaine
* Vérifier que l'email est correct, et qu'il n'est pas déjà présent en base de donnée afin d'éviter des doublons de compte.
* Hasher le mot de passe et stocker la version hashée, de façon à ne jamais pouvoir récupérer le mot de passe réel. À la connexion, on procédera pas comparaison de la version hashée et non à la comparaison du mot de passe en clair.

### Exemple

Il s'agit ici d'un exemple très simple et rapide. Des tests plus complémentaires plus poussés peuvent être appliqués (comme un retour plus fin sur chaque champ en erreur).
```
$email = trim($_POST['mail']);
$password = trim($_POST['password']);
$register_date = time();

if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Vérification si présent en base, si non insertion
}
```

## Hashage de mot de passe

En appliquant un hachage sur le mot de passe avant de le stocker, vous rendez la tâche d'un attaquant très difficile pour connaître le mot de passe original, et vous avez toujours la possibilité de comparer le mot de passe haché à une chaîne reçue.

PHP 5.5 fournit une API native de hachage de mot de passe qui gère à la fois le hachage et la vérification de mots de passe, le tout, de manière totalement sécurisée.

Avant la fonction mise à disposition par PHP 5.5, la méthode consistait à hacher le mot de passe via les fonctions `md5()` ou `sha1()`, et généralement de les faire en les concaténant à un salt. Désormais, il est recommandé d'utiliser la fonction `password_hash()` pour hacher le mot de passe, puis `password_verify()` pour vérifier à la connexion que le mot de passe saisi correspond à la version hachée stockée en base de donnée.

### Hachage

La fonction `password_hash()` va donc s'occuper elle-même de générer un salt (grain de sel, permettant de complexifier le hachage de la chaîne), puis de hacher la chaîne de caractères qui lui sera passée. Elle générera enfin une chaîne de caractère contenant toutes les informations nécessaires à l'utilisation de la fonction de vérification.
```
$password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

// On stock ensuite le contenu de $password en base de donnée
```

### Vérification

La fonction de vérification `password_verify()` reçoit en paramètres la chaîne à comparer, et la version hachée que l'on a normalement stockée en base de donnée.
Elle renverra alors true si elle obtient le même résultat en réutilisant les mêmes propriétés pour hacher le mot de passe.
```
// Dans cet exemple, on précise la chaine de caractères, mais elle serait normalement récupérée depuis la base de données
$db_password = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq';
$form_password = trim($_POST['password']);

if (password_verify($form_password, $db_password)) {
    // Le password saisi par l'utilisateur correspond bien à celui stocké en base de donnée sous form de hash
} else {
    // Le password ne correspond pas, échec de la connexion de l'utilisateur
}
```

### Documentation

Une [page dédiée](http://php.net/manual/fr/faq.passwords.php) au hachage de mot de passe est disponible sur la documentation PHP, et détaille notamment comment est composé la chaîne de caractères générée par `password_hash()`.

## Authentification de l'utilisateur

L'authentification d'un utilisateur est l'ensemble des opérations à réaliser afin que l'application sache qui est l'internaute naviguant sur le site. Le processus classique d'authentification se réalise en plusieurs étapes :

* Dans un premier temps, il faut vérifier si l'utilisateur est présent dans la base de données (s'il est déjà inscrit), habituellement grâce à un pseudo ou à un email
* Ensuite, il faut ensuite vérifier si le mot de passe fourni est le bon
* Finalement, il faudra créer une session pour cet utilisateur, afin de ne pas lui demander de s'authentifier à chaque requête

Exemple
```
<?php
session_start();  // démarre la gestion de session PHP
if (isset($_POST['login'], $_POST['password'])) {
    $db = new PDO('mysql:host=localhost;dbname=wf3', 'login', 'password');

    // Nous partons du principe dans cet exemple que le login de l'utilisateur est son mail
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    // Pour éviter une requête inutile, on peut vérifier que l'utilisateur a bien saisi un mail valide et que le password n'est pas vide.
    if (filter_var($login, FILTER_VALIDATE_EMAIL) && !empty($password)) {

        // On va chercher en base de donnée l'ID, le prénom, le nom et le mot de passe haché correspodnant au mail spécifié
        $query = $db->prepare('SELECT id, firstname, lastname, password FROM users WHERE mail = :login');
        $query->bindValue(':login', $login, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetchAll();

        // On compte le nombre de résultats retournés (1 si l'utilisateur existe) et on compare le mot de passe à sa version hachée
        if (count($user) > 0 && password_verify($password, $user[0]['password'])) {

            // L'utilisateur existe bien en base de donnée avec le mail spécifié, et le mot de passe correspond, il est donc authentifié
            $_SESSION['user'] = $user[0]['id'];
            $_SESSION['user_fullname'] = $user[0]['firstname'] . ' ' . $user[0]['lastname'];

        } else {

            // Utilisateur non trouvé ou mot de passe incorrect

        }
    } else {

        // Informations de connexion incorrectes

    }
}
```

## Les sessions

### L'utilité des sessions

PHP est un langage non connecté, donc à la fin du chargement de la page, la connexion prend fin, et on perd donc toutes les informations.
Les sessions sont apparues depuis PHP4 pour palier à ce problème, elles servent à véhiculer les informations propres à un utilisateur sur l'ensemble du site.

Les sessions sont souvent utilisées pour stocker l'utilisateur courant mais elles peuvent aussi servir pour stocker toutes autres informations (préférences utilisateurs, pages visitées, etc.).

### Comment ça fonctionne ?

Tout nouvel utilisateur d'un site se voit attribuer une nouvelle session avec un ID unique de session. Grâce à cet ID de session, PHP vas pouvoir accéder aux informations stockées dans cette session pour ce même utilisateur. Les sessions expirent en moyenne après 30 minutes d'inactivité (cela dépend de la configuration PHP).

#### `session_start()`

Il faut absolument appeler la fonction `session_start()` avant tout output de PHP pour accéder aux informations de la session en cours.
Ces informations sont stockées dans la variable superglobale `$_SESSION`.
```
session_start();
print_r($_SESSION); // Affiche toutes les informations dans la session courante
```

### Comment manipuler la variable $_SESSION ?

Elle se manipule comme toute variable superglobale, et plus précisément comme un simple tableau associatif.
```
// Contrairement aux autres variables superglobales, on doit instancier la variable $_SESSION avec cette fonction afin d'éviter une erreur PHP.
session_start();

// Enregistrez un valeur en session
$_SESSION['ma_cle_tableau'] = 'toto';

// Accès à une variable de session
echo $_SESSION['ma_cle_tableau'];

// Supprimez une variable de session
unset($_SESSION['ma_cle_tableau']);
```
### Comment authentifier un utilisateur avec `$_SESSION` ?

Il est conseillé de stocker en session l'ID unique de l'utilisateur connecté. A partir de cet ID, nous pourrons retrouver toutes ses informations à partir d'une base de données par exemple.

Pour stocker cette ID, il faut en premier lieu vérifier s'il n'y a pas déjà un ID d'utilisateur en session. S'il y a pas d'ID, nous demandons à l'utilisateur de s'authentifier via un formulaire. Si les informations du formulaire sont correctes(login/mot de passe), nous récupérons l'ID à partir de ces informations et nous le stockons en session.

On peut considérer à ce stade que l'utilisateur est désormais connecté sur son site car nous avons bien stocké son ID unique afin de pas lui redemander à chaque changement de page.

## Les sessions et la sécurité

### Cookie de session

Pour que PHP puisse récupérer les informations d'une session existante, il doit récupérer l'ID session unique de l'utilisateur.
Souvent cette ID est stocké dans un cookie qui est un petit fichier que l'on enregistre sur l'ordinateur du visiteur.

A chaque requête à une page sur le site, l'utilisateur va transmettre son numéro ID de session à PHP via le cookie de session.

### Usurpation de session

Il est possible pour un utilisateur malveillant d'usurper votre identité avec les sessions.
En effet, s'il parvient d'une manière ou d'une autre à récupérer le cookie sur votre ordinateur, il peut se faire passer pour vous.

Imaginez que vous venez de vous connecter sur votre site et votre ID est enregistré dans la session courante pour dire que vous êtes connecté. Le malveillant n'a plus qu'à voler votre cookie pour se faire passer pour vous.

Le serveur PHP ne fera aucune différence entre vous et lui car il ne s'appuie que l'ID de session unique qui est dans le cookie pour récupérer les informations de la session.

### Comment protéger vos utilisateurs ?

Pour protéger vos utilisateurs de ces vols, il faut pouvoir répondre à cette question : Que différencie le bon du mauvais utilisateur ?

Pour cela, à chaque création de session, on doit stocker en session des informations propres au bon utilisateur. Ensuite lorsqu'un utilisateur présente un ID de session, on vérifie si ses informations correspondent bien à ceux que nous avons enregistrées au préalable.

L'enregistrement de l'adresse IP apparaît comme une solution simple et rapide. En effet, une machine correspond à une session qui correspond un utilisateur. Si l'IP change, cela signifie que la session a été volée et utilisée sur une autre machine.
