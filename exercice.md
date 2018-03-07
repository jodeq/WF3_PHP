# Exercice de préparation :

## 1. Fiche du dresseur

Dans une page dresseur.php, Créez un tableau contenant les informations suivantes :
* Prénom
* Nom
* Adresse
* email
* Date de licence de dresseur au format anglais (YYYY-MM-DD)

Affichez les informations de ce tableau dans une liste HTML `ul`.

La date de licence sera à afficher au format français (DD/MM/YYYY) en utilisant la classe [DateTime](http://php.net/manual/fr/class.datetime.php)

Pas de bases de données pour cet exercice

## 2. Préparation du stockage

### Ecrivez un script SQL pour créer la table de l'exercice précédent :

Créez une base de donnée `Dresseurs` avec phpmyadmin et dans un fichier .sql le script de création de la table `dresseur` qui contiendra les colonnes suivantes :
* id (int + auto increment + primary key)
* prénom (varchar)
* nom (varchar)
* adresse (varchar)
* email (varchar + index unique)
* date_licence (date)
* arene_prefere (enum) (liste des valeurs : Argenta, Azuria, Carmin-sur-Mer, Céladopole, Parmanie, Safrania, Cramois'Île, Jadielle)

### Créez un formulaire d'ajout de dresseur dans la table

**Contrôles des données**
* le prénom, l'email, la date de licence et l'arene préférée sont obligatoires
* la date de licence est inférieure à la date du jour
* l'email est valide (filter_validate)
* le prénom fait au moins 3 caractères de long
* l'arene préférée est une liste html `select`

**Comportements**
* En cas d'erreur de saisie les champs en erreurs doivent être signalés (encadrement rouge) et un message doit préciser l'erreur
* En cas de succès le dresseur est ajouté en base et un message de réussite en vert confirmera l'insertion

### Affichez la liste des dresseurs

Créez une page php qui affichera la liste des dresseurs avec les informations suivantes :
* prénom
* nom
* date_licence
* un lien html `plus d'info`

En cliquant sur le lien `plus d'info` affichez toutes les informations du dresseur sélectionné :
* prénom
* nom
* adresse
* email
* date_licence
* arene_prefere

Cette page plus d'info doit être dynamique et si le dresseur demandé n'existe pas affiché `Aucun dresseur trouvé`.


## 3. Achat inapp : conversion

Dans le cadre de notre application pokemon, les dresseurs peuvent acheter des pokedollars ![one pokedollars](http://strategicalblog.com/wp-content/uploads/2012/11/pokedollar-strategicalblog.png)
> credit : http://strategicalblog.com

Le taux de conversion Euro/Pokedollar est de 1€ = 1.23P<br>
Le taux de conversion Dollar/pokedollar est de 1$ = 1.05P

Dans un fichier PHP créez une fonction permettant de convertir vos devises (euro ou dollar) en pokedollars

Les paramètres de cette fonction doivent être :
* le montant à convertir
* la devise du montant (USD ou EUR)

Votre fonction affichera : `[montant] [devise] = [montant converti] pokedollars`

avec montant le premier paramètre, devise le second paramètre et montant converti le résulat de la conversion.


### 4. La chasse au pokemon

Créez une table `pokemons` qui contiendra les champs suivants :
* id (int + auto increment + primary key)
* nom (varchar)
* type (enum) (liste des valeurs : 'plante', 'feu', 'électrique', 'eau', 'normal')
* pv (int) : points de vie
* defense (int)
* attaque (int)

Ensuite vous pourrez passer le script SQL suivant pour ajouter des pokemons
```
INSERT INTO pokemons(nom, type, pv, defense, attaque) VALUES 
('bulbizarre', 'plante', 45, 49, 49),
('salamèche', 'feu', 39, 52, 43),
('pikachu', 'électrique', 35, 55, 40),
('rattata', 'normal', 30, 56, 35),
('carapuce', 'eau', 44, 48, 65);
```

Créez ensuite la table d'association N-N qui permettra de savoir quel dresseur possède quel pokemon (ou inversement)

La table `dresseur_pokemon` contiendra les champs suivants :

* id_dresseur (int) : clé étrangère sur le champ `id` de la table `dresseurs`
* id_pokemon (int) : clé étrangère sur le champ `id` de la table `pokemons`
* date_capture (date)
* la clé primaire sera le couple (id_dresseur, id_pokemon)

Exportez les déclaration des 2 tables dans un fichier SQL.

Maintenant dans la page de détail d'un dresseur pokemon afficher la liste de ses pokemons

Puis dans un formulaire :
* Listez les pokemons non capturés
* Ajoutez un bouton `Capturer` qui associera le pokemon choisi à ce dresseur

S'il n'y a plus de pokemon libre, n'affichez pas ce formulaire.

