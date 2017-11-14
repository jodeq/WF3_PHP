# Authentification et autorisation, partie 2

## Système d'oubli du mot de passe

### Oubli du mot de passe

Lorsque vos utilisateurs oublient leur mot de passe, vous devez mettre en place un système pour leur permettre de définir un nouveau de passe en toute sécurité.
En effet, vous ne pouvez pas simplement leur renvoyer leur mot de passe car c'est un mot de passe encodé qui est enregistré en base de données pour une question de sécurité et de confidentialité.

### Importance de l'email

Il est primordial que vous connaissiez l'email de vos utilisateurs sans quoi le système d'oubli du mot de passe ne pourra pas marcher.
C'est notre seul moyen de communiquer avec l'utilisateur et lui transmettre la procédure à suivre pour récupérer son compte.

### Etape 1 : Récupération et vérification de l'email

Il faut rediriger l'utilisateur qui a oublié son mot de passe vers une page avec un formulaire où il devra remplir son email avec lequel il s'est enregistré sur le site.
A la soumission du formulaire, on doit vérifier l'existence de cet email. Si l'email n'existe pas en base de donnée, on ne pourra pas continuer le processus.

### Etape 2 : Génération d'un token pour cet email

Une fois qu'on s'est assuré de l'existence de l'email en base de données, on va générer un token qui va permettre au client de définir son nouveau mot de passe.

Un token (ou jeton) est une chaîne de caractères alphanumérique, généralement temporaire. Elle doit être assez longue pour assurer un niveau de sécurité acceptable. On peut comparer le token à une clé wifi.
On va ensuite relier ce token à l'email en base de données.

Pour générer un token, vous pouvez utiliser les instructions suivantes :
```
$token = md5(uniqid(rand(), true));
```

### Etape 3 : Envoi d'un email à l'utilisateur

On va envoyer à l'utilisateur un email pour lui dire que nous avons bien enregistré sa demande pour l'oubli de passe.
Dans cet email, nous allons également lui fournir une URL du site pour qu'il définisse son nouveau mot de passe.

L'url se fera de la forme suivante : http://www.monsiteweb.fr/motdepasse_oublie.php?id=7&token=a5f8eksgfoe4g2z8gksbruvnzg79zajfiajf5zfjzjg52z

On peut voir que l'url comporte 2 paramètres en GET, l'id du client à partir duquel on peut récupérer l'email en base de données et le token qu'on a généré à l'étape précédente.

### Etape 4 : L'utilisateur définit son nouveau mot de passe avec l'url reçu

L'utilisateur va arriver sur la page de redéfinition de son mot passe lorsqu'il clique sur le lien dans l'email.
On va maintenant vérifier que les informations renseignées (id et token) sont valides avant de lui permettre de définir son nouveau de passe.

On va chercher en base les données, les informations du client avec l'id renseigné et nous allons comparer le token renseigné dans l'url et celui enregistré en base de données.
Si ils concordent, c'est le bon utilisateur et non quelqu'un qui veut essayer de voler un compte. Le client n'a plus qu'à renseigner son nouveau mot de passe à travers un formulaire. On efface ensuite le token pour terminer le processus.

Au final, le procédé est assez proche de l'authentification de l'utilisateur, si ce n'est que ce sont pas les mêmes informations qui sont vérifiées.
Ici cela correspondrait presque à un système d'authentification par url.
Conseil

Il est recommandé d'ajouter une date d'expiration au token, par exemple de 3 jours pour augmenter la sécurité de ce processus. Au bout de 3 jours, si le client n'a pas redéfini son mot de passe, le token expire et il devra refaire le processus depuis l’étape 1.

## Envoi d'email en PHP

### Envoi de mails avec la fonction `mail()`

La fonction `mail()` permet d'envoyer simplement un mail en renseignant le destinataire, le sujet, le contenu du mail.
```
mail('destinataire@mail.com', 'Mon Sujet', 'Mon message', $headers);
```

### Mail au format HTML

Pour envoyer un mail au format HTML, il faut spécifier le header suivant :
```
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
```
Les en-têtes (headers) permettent de spécifier des options pour l'envoi du mail.
Vous pouvez désormais intégrer le corps de votre mail en format HTML qui sera bien interprété pour le client mail.

### Les options disponibles

Dans la variable `$headers`, passée en 4e argument de la fonction mail, vous pouvez définir quelques options utiles :
```
From -- (Envoyeur)
Reply-To -- (Répondre à)
Content-type -- (texte, html, etc...)
Cc -- (Copie à)
```

### Les bonnes pratiques

Renseignez toujours les bonnes informations dans les headers de vos mails, et évitez de spammer vos utilisateurs.
Vous risquez de blacklister votre serveur d'envoi de mails.
Les limites de la fonction `mail()`

Sur certains serveurs mutualisés, le nombre de mail envoyé par mois est limité. Passé cette limite, vous ne pouvez plus envoyer de mails.
De plus, les mails envoyés avec la fonction mail finissent assez souvent dans le dossier spam des destinataires.
Utiliser un serveur SMTP (Simple Mail Transfer Protocol) pour envoyer ses mails

Un serveur SMTP est un serveur qui va envoyer les mails à la place de votre serveur.
Il va soulager la charge de votre serveur si vous envoyez beaucoup de mails.
De plus il n'y pas de limitations sur le nombre de mails envoyés et souvent ces serveurs sont reconnus et donc vos mails ne finiront pas dans le dossier spam du destinataire.

### Envoi de mails avec PHPMailer via un serveur SMTP distant

Afin de faciliter la tâche de l'envoi des mails en PHP, on peut utiliser la célèbre [librairie PHPMailer](https://github.com/PHPMailer/PHPMailer).

Example d'envoi d'un email simple avec PHPMailer :
```
<?php
    // On inclut la librairie de PHPMailer
    require 'PHPMailerAutoload.php';

    $mail = new PHPMailer();

    // Utilisation d'un SMTP pour envoyer les mails
    $mail->isSMTP();
    $mail->Host = 'smtp.monsite.fr';

    // Si votre SMTP a besoin d'un authentification
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mon_login';
    $mail->Password   = 'mon_mot_de_passe';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Envoyeur et destinataires du mail
    $mail->setFrom('moi@monsite.fr', 'Moi');
    $mail->addAddress('joe@example.net', 'Joe User');
    $mail->addCC('cc@example.com');
    $mail->isHTML(true);

    // Contenu du mail
    $mail->Subject = 'Le sujet du mail';
    $mail->Body    = 'Le corps du mail avec des balises html <b>prise en charge!</b>';

    // Envoi du mail
    if(!$mail->send()) {
        echo 'Votre email n\'a pas pu être envoyé';
        echo 'Erreur: ' . $mail->ErrorInfo;
    } else {
        echo 'L\'email a été envoyé';
    }
```

## Autorisation

### Les ressources disponibles pour un utilisateur connecté

Lorsqu'un utilisateur se connecte à votre site, vous devez être en mesure de lister toutes les actions disponibles pour cet utilisateur.
Un exemple simple serait de comparer les actions disponibles pour un administrateur d'un forum et un simple utilisateur.

Un administrateur peut avoir accès à plus de ressources qu'un simple utilisateur. Il faut interdire à l'utilisateur d'accéder à des ressources disponibles pour administrateurs comme la modération par exemple.

On voit tout de suite l'importance de mettre une étiquette pour chaque utilisateur afin de limiter ses actions.
Il est d'usage d'associer un utilisateur à un groupe. Un groupe est un ensemble d'utilisateur qui a les mêmes rôles.

### Le concept de "rôle" d'un utilisateur

Les rôles permettent de donner/limiter des droits sur des actions réalisées sur le site.
Un modérateur d'un forum se verra par exemple attribuer le rôle de role-moderation-commentaire et le rôle role-poster-message alors qu'un simple utilisateur aura seulement le rôle role-poster-message.

Lorsqu'un utilisateur essaie de faire une action non autorisée par son rôle, on doit lui en empêcher en lui envoyant une erreur 403.

### L'erreur 403

L'erreur 403 est un code HTTP correspondant au statut 'Forbidden'', cela permet de spécifier, via le code HTTP que l'accès est interdit. On alerte ainsi l'utilisateur par cette page qu'il tente de faire une action interdit par son rôle.
On peut ensuite personnaliser cette page de façon à afficher une erreur plus propre qu'un simple "Forbidden" pour informer l'utilisateur et rester dans la charte graphique du site.

Pour afficher un page avec un code d'erreur 403 en PHP :
```
<?php
header('HTTP/1.0 403 Forbidden');
die('Vous n\'avez pas les permissions pour accéder à cette ressource');
```
