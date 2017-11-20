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

Dans un fichier PHP créez une fonction permattant de convertir des euros vos devises en pokedollars

Les paramètres de cette fonction doivent être :
* le montant à convertir
* la devise du montant (USD ou EUR)

Votre fonction affichera : `[montant] [devise] = [montant converti] pokedollars`

avec montant le premier paramètre, devise le second paramètre et montant converti le résulat de la conversion.
