# Hébergements et domaines

## FTP

Pour mettre en ligne un site internet, il faut uploader les fichiers sources sur le serveur en ligne.
Pour cela, le plus facile est de passer par un client FTP (File Transfert Protocol).
Les plus connus et gratuits sont FileZilla, WinSCP, et FTP Voyager.

### Configurer son client FTP

Pour se connecter au serveur via le client FTP, il faut renseigner plusieurs informations, qui sont généralement fournies par l'hébergeur :

* Hôte (Adresse IP ou url, type http://ftp.monsite.com)
* Port (21 en mode non sécurisé, et 990 en sécurisé)
* Protocole (FTP ou SFTP(sécurisé))
* Type d'authentification (Anonyme ou avec login/mot de passe)
* Mode de transfert (Actif par défaut ou passif si le client est derrière un pare-feu)
* Votre identifiant
* Votre mot de passe

### Configurer FileZilla

Nous allons utiliser FileZilla pour nos transferts de fichiers sur le serveur. Les autres logiciels ont sensiblement les mêmes options de configuration.
Pour configurer une nouvelle connexion sécurisée il faut suivre ces étapes :

Aller dans le menu Fichier puis cliquer sur Gestionnaire de sites
La liste des connexions est affichée, cliquer sur Nouveau Site pour créer un nouvelle connexion
Donner un nom à cette connexion et renseigner les options suivantes (l'hôte, le protocole FTP, le type authentification normal, le login et enfin le mot de passe)
Il ne reste plus qu'à cliquer sur Connexion.
Si tous les paramètres sont bons, il devrait y avoir dans le panneau de droite la liste des fichiers du serveur, ceux présents dans le dossier auquel le login a accès par défaut à la connexion.

### Charger et télécharger des fichiers

Sous FileZilla, le chargement et le téléchargement des fichiers est aussi simple que de déplacer des fichiers d'un dossier à l'autre dans l'explorateur Windows: il suffit de glisser déposer les fichiers dans un sens ou un autre (du volet de droite au volet de gauche pour télécharger, l'inverse pour envoyer).
Il est également possible de faire un clic droit sur un fichier ou un dossier et choisir Envoyer ou Télécharger le fichier ou le dossier.

### Changer les droits d'accès sur les fichiers et répertoires

Pour contrôler les droits d'accès sur les fichiers et répertoires, vous devez leur donner les bonnes permissions.
Pour cela rien de plus simple, faites un clic droit sur le ficher ou le dossier et cliquez sur Droits d'accès au ficher ou Attribut du ficher.
Ensuite, vous pouvez définir les droits, Lire, Ecrire, et Exécuter selon le groupe d'utilisateur. Cliquez sur Ok pour prendre en compte vos changements.
Il faut généralement éviter de donner tous les droits sur des fichiers, tout particulièrement des fichiers sensibles. Il est important de veiller à ce que des droits très permissifs soient donnés avec parcimonie.

## Décryptage des offres d'hébergements mutualisés

### Les avantages des hébergements mutualisés

Pour mettre votre site en ligne, il vous faudra un hébergement en ligne.
De nombreux sites renommés vous proposent ce service, citons notamment ovh.com, gandi.net et 1and1.fr.
Ces sites vous propose un large choix d'offres d'hébergements mutualisés.
Les avantages de l'hébergement mutualisé sont :

* Coûts faibles à modérés
* Toutes les interventions techniques sont à la charge de l'hébergeur
* Aucune connaissance d'administration avancée requise
* Nombreux services annexes inclus

### Comment choisir son hébergement mutualisé ?

Avant de choisir son hébergement mutualisé, il faut définir vos besoins.
Commencez par définir vos besoins à la fois au niveau technique et au niveau des services que vous attendez.

Dans les options, vous aurez le choix de l'espace disque. En effet, entre un site qui héberge des vidéos ou des images et un site qui ne fait que du blogging, le besoin en espace disque peut énormément varier.

Votre langage de programmation peut aussi vous influencer dans votre choix. L'hébergeur doit proposer les dernières versions de votre langage correspondant à vos pré-requis (exemple PHP 5.4).
Les autres langage ne sont pas non plus à prendre à la légère. Pensez à l'évolution de votre site, peut être que votre site aura besoin d'un autre langage de programmation non pris en charge par l'hébergeur.

Un autre critère décisif est celui de la base de données. Une base de données est essentielle pour un site dynamique. Vérifiez la taille maximale de la base de données. Les contenus dynamiques consomment en général beaucoup d'espace. Le nombre de connexions simultanées est aussi important car il risque de pénaliser les visiteurs (ils ne pourront pas accéder à votre site s'il y en a beaucoup en même temps) s'il est trop faible.

La bande passante (ou trafic) est aussi à prendre en compte quand celui-ci n'est pas illimité. Là encore, un site hébergeant beaucoup de vidéos ou de photos haute définition consommera d'avantage de bande passante qu'un site affichant majoritairement du contenu textuel.

Dans les services proposés, on peut notamment voir si le multi site (plusieurs domaines pouvant être liés) est supporté ou encore le planificateur de tâches (possibilité de lancer des tâches récurrentes ou non, comme l'exécution d'un script en particulier).

Bien entendu le critère le plus important est le coût. Il faut avoir le meilleur rapport qualité prix possible. Pour un site personnel simple ne visant pas un traffic très élevé, il peut se situer aux alentours d'une dizaine d'euros par mois.

### Les limites des hébergements mutualisés ?

La présence d’autres sites web sur un même serveur peut compromettre la sécurité et la performance de votre site.
De plus les limitations sur l'utilisation des ressources (processeur, mémoire vive, bande passante) ne sont souvent pas précisées et vous pouvez rapidement les atteindre si votre site représente une charge significative pour le serveur en parallèle des autres sites hébergés.

Lorsqu'il y a un problème quelconque sur votre site, vous n'avez pas la main mise sur le serveur. Vous devez attendez que le service technique prenne en charge votre erreur, qui entrainera souvent une indisponibilité à durée indéterminée, pouvant avoir un impact sur le ressenti de vos visiteurs et le référencement.

La configuration du serveur (PHP, etc.) est aussi souvent extrêmement restreinte voir totalement bloquée, ce qui peut être gênant dans l'utilisation de certains scripts demandant une configuration particulière.

La solution serait de prendre un hébergement dédié mais il vous faudra un coût beaucoup plus élevé et des connaissances techniques avancées dans la gestion des serveurs.
L'hébergement mutualisé est la solution idéale pour démarrer son site en ligne, à faible coût, sans se soucier des problématiques avancées de gestion d'un serveur.
