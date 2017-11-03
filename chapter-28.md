# Gérer son code grâce à Git et Github

## Git

### Versionner son code

Git est un outil qui permet de versionner son code. Ce logiciel a été créé par Linus Torvald, auteur du Kernel Linux.

Versionner veut dire : gérer les versions de son code au fur et à mesure de ses modifications.

But :

- Pouvoir revenir en arrière en cas d'erreur
- Travailler en équipe
- Travailler sur des versions annexes de son projet
- Comprendre l'évolution d'un projet

Chaque série de modification sera enregistrée dans un COMMIT.

###### EXERCICE : Vous travaillez sur un formulaire de landing page, imaginez 3 séries de modifications que vous allez apporter et nommez vos commits.

##### Un commit = version du code à un instant donné.

Tous les commits additionnés entre eux constituent l'historique de votre projet.

>"Si le code n'est pas enregistré dans un logiciel de gestion de version, il n'existe pas."

Jeff Atwood, créateur de Stack Overflow.


Un logiciel de gestion de versions est un outil essentiel pour un développeur.
Il en existe plusieurs, basés sur deux modèles :

- le modèle centralisé : un serveur central contrôle toute la base de code du logiciel (SVN, CVS).

- le modèle distribué : toutes les machines ont accès à la base de code, le serveur central n'est pas utile (Mercurial, Bazaar).

##### Git utilise un modèle distribué.

Avantages de ce modèle :

- Code accessible par plusieurs sources donc pas de pertes.
- Possibilité de travailler hors connection.
- Echanges et collaboration au sein de la communauté.

### Installer Git

Git s'installe à l'aide de la console.

Lien de téléchargement : http://git-scm.com/downloads

Ligne de commande sous Mac :

```
git config --global user.name "Votre nom ou pseudo"
git config --global user.email "Votre@email.com"
```

Ligne de commande sous Linux :

```
git config --global user.name "Votre nom ou pseudo"
git config --global user.email "Votre@email.com"
```

Sous Windows :

Lien de téléchargement : http://msysgit.github.io

Laissez toutes les configurations par défaut de la console.

Ouvrez l’application “git bash” qui se situe dans votre menu Démarrer.

Ligne de commande :

```
git config --global user.name "Votre nom ou pseudo"

git config --global user.email "votre@email.com"
```

Relancez votre console et tapez `git`. Si tout va bien, un texte explicatif en anglais apparaît.

### Faire un commit

###### EXERCICE : Créez un nouveau dossier repository.

Lignes de commande :

```
cd
mkdir monDossier
git init
git add nomDeVotreFichier.extension
git commit -m "Description des modifications"
```

### Lire l'historique

Commande :

```
git log
```

Le sens de lecture : du commit le plus récent en haut de la liste, au commit le plus ancien en bas de la liste.

### Se positionner

```
git checkout SHA_du_commit
ou
git checkout master
```

En cas d'erreur :

```
git revert SHA_du_commit
git commit --amend -m "Le nouveau commentaire"
ou
git reset --hard
```

### Les remotes :

Utile pour avoir un backup de sa machine.

C'est une autre machine sur laquelle on envoie ses commits.

Elle peut être interne ou externe, en utilisant Github ou BitBucket.

Pour gérer les dépôts distants :
* `fetch` : récupère dans son dépôt local les modifications distantes
* `pull` : comme pour `fetch` mais en plus fusionne avec la branche locale (`merge`)
* `push` : envoie les modification du dépôt local vers le dépôt distant

### Les branches

Une branche permet de travailer de manière isolé du reste du projet.

`git branch <branche>` pour la création d'une branche puis `git checkout <banche>` pour s'y déplacer ou en une seule opération `git checkout -b <branche>`.

Une fois le code de la branche validé
* `git rebase` pour repositionner la branche en haut de la branche de destination et résoudre les conflits
* `git merge` pour fusionner la branche de la fonctionnalité dans la branche principale

exemple :
```
# Sur la branche fonctionnalité <branche>
# rebase sur la branche master
git rebase master
# Déplacement dans la branche master
git checkout master
# Fusion de la fonctionnalité
git merge <branche>
```

## Github :

GitHub est un service en ligne de dépôt Git, qui permet d'héberger ses repositories de code.
On peut y héberger gratuitement du code open source et il propose des solutions payantes pour des projets privés.

https://github.com/

Servez-vous en pour :

- Copier un dépôt sur le compte utilisateur de celui qui l'exécute, afin de lui permettre d'y apporter des modifications de son côté (**fork**)
- Communiquer avec d'autres développeurs et signaler des problèmes de code (**issues**)
- Partager des morceaux de code en ligne (**gists**)
- Proposer des modifications de code à d'autres (**pull requests**)
- Créer des pages de contenus liées au dépôt sans y être directement présents en tant que fichier ou de la documentation sur le projet (**wiki**)
- Mettre en ligne un site statique (**pages**)
- Récupérer du code depuis un autre repository

### Installation et configuration :

Deux façons d'intéragir avec le dépôt Git :

- En ligne de commande (CLI)
- Via une interface graphique (GUI)

GitHub Desktop : interface graphique de Github qui fonctionne avec tout type de dépôt Git.

=> desktop.github.com puis Download GitHub Desktop.

Installation :

 - double-clic sur le fichier téléchargé
 - clic sur Install
 - clic sur Run

Configuration :

Indiquez les informations de votre compte GitHub.

### Utilisation de base de Github Desktop :

#### Créer un repository :

File > New Repository > Publish

#### Clonage d'un repository :

Exemple avec le dépôt de jQuery:

- github.com/jquery/jquery
- Clic sur le bouton à gauche Clone in Desktop de Download Zip
- Choisissez le répertoire dans lequel vous souhaitez placer le dépôt cloné localement
- Validez

Méthode pour un dépôt sur votre compte Github:

File > New Repository > Clone  > selection

#### Commit & Push (Sync)

Dans le volet de gauche se trouve la liste des fichiers modifiés, avec à droite une barre de progression des changements effectués.
Le vert = les ajouts, le rouge = les suppressions.

Le diff est le différentiel entre ces deux fichiers.

Summary =  message résumant de façon très courte la modification

Description = message plus long résumant la modification.

Envoyer l'ensemble des commits locaux vers le dépôt distant :  Commit to > Sync

#### Historique

History : contient la liste des commits du dépôt distants synchronisés avec le dépôt local. Après sélection d'un commit dans la liste,
le volet de droite reprend la liste des fichiers modifiés dans le commit et le diff de chacun des fichiers.

### Github : Workflow simple en équipe :

#### Ajouter des collaborateurs

Pour les gros projets, il est plus pratique de créer une équipe (Team).
Pour les petits projets l'ajout en tant que collaborateur peut suffire.

Github.com > Settings > Collaborators & teams > Nom d'utilisateur > Add a collaborator.
Si vous voulez qu'il effecute des push, selectionnez "Write".

#### Workflow

Deux exemples de méthodes de travail sur Git :

- Workflow simple :

Pour un petit nombre sur un projet de petite à moyenne taille.

=> effectuer des push les uns après les autres sur la même branche.
Faire régulièrement faire des pull pour mettre son code à jour avant de faire des push ou après un commit, s'il y en a beaucoup qui n'ont pas été envoyées sur le dépôt distant.

- Worflow avancé :

Recommandé par GitHub.

Sur un projet plus complexe, avec des phases de déploiement de versions stables.

Le but est d'éviter de casser la version existante ou le projet dans sa globalité.

Pour cela, vous devez utiliser les branches.

Une par nouvelle fonctionnalité.

Les commits et les push sont faits sur la branche.

Elle sera fusionnée avec la branche principale au moment du déploiement.

=> Pull Request : demande de fusion d'une branche avec une autre.

Commentaires du groupe sur les modifications > accord >  Merge pull request pour fusionner la branche ou fermer la Pull Request.

 - Les conflits :

Si modification des mêmes lignes dans un même fichier par deux collaborateurs : fichier marqué en conflit et commits bloqués tant que le conflit n'est pas résolu.

Ligne de commande :

```
CONFLICT (content) : Merge conflict in <your_file.ext>
```

GitHub Desktop = cases en jaune.

Résoudre un conflit :

contenu du fichier :
```
Le nombre de fruits est
<<<<<<< HEAD
six
=======
cinq
>>>>>>> branch-a
```
Pour résoudre le conflit : remplacer tout le bloc par ce qui vous semble correct.

Ligne de commande :

```
git add
```

Dans GitHub Desktop :

clic droit puis "Mark as resolved" sur le fichier dans la liste des changements.
