# Projet en équipe, partie 2

## Utilisation d'API

Les API (pour Application Programming Interface, ou Interface de Programmation) qui désignent principalement sur internet des webservices permettant aux développeurs externes d'interagir avec les contenus ou les services qu'un site met à disposition.

Ces API sont presque devenues systématiques dès qu'un nouveau service se lance (ou peu de temps après) lorsqu'il y a un intérêt à pouvoir utiliser les données ou services mis à disposition.

Pratiquement tous les sites web que vous utilisez au quotidien mettent à disposition une API: Facebook, Twitter, Google (Search, Maps, Drive, Plus, Gmail, etc.), Instagram, Github, etc.

Les API peuvent être utilisées à titre personnel (si vous souhaitez faire un outil pour vous seulement par exemple) ou à titre public ou commercial (suivant les conditions et licences de celui qui met à disposition l'API).

**Exemple**

Vous pourriez par exemple créer une formulaire avec un champ texte qui, lorsqu'il est rempli et envoyé, irait publier son contenu sur Facebook, Twitter et Google Plus en même temps.
Il vous faudrait alors utiliser les API de Facebook, Twitter et Google Plus séparément.

Vous pourriez également récupérer tous les derniers tweets géolocalisés pour les afficher sur une carte Google Maps.

### Protocole

Même si le nom est commun, toutes les API ne sont pas identiques.
Le service est libre de choisir le nom des méthodes qu'il veut mettre à disposition, le format renvoyé, le langage à utiliser, etc.

Il existe néanmoins quelques protocoles permettant d'avoir une base standard, comme SOAP, RPC, ou plus récemment REST.

### API REST

Une API REST est établie sur plusieurs principes:

* Des verbes HTTP pour décrire le type d'action qui est fait (lire, créer, mettre à jour, etc.)
* La version de l'API
* Une structure d'URL basée sur le principe de collection (plusieurs éléments) et d'instance (un seul élément)
* Les codes HTTP pour indiquer le statut de la réponse de l'API.

**Exemple**

Prenons par exemple une API pour gérer des factures.
Vous aurez alors les urls suivantes, dont les actions vont être différentes selon le verbe HTTP utilisé.

|Verbe HTTP|Collection: /v1/factures|Instance: /v1/factures/5618|Code HTTP
|----|----|----|----|
|`GET`|Liste toutes les factures|Récupère le détail de la facture 5618|200 OK|
|`POST`|Créé une nouvelle facture|-|201 Created|
|`PUT`|-|Mise à jour de la totalité de la facture|200 OK|
|`PATCH`|-|Mise à jour partielle de la facture|200 OK|
|`DELETE`|-|Suppression de la facture|200 OK|

Ici, v1 est la version de l'API, factures est l'objet avec lequel on interagit, et dans le cas où 5618, il s'agit de l'ID de l'instance de l'objet avec laquelle on interagit. Le tout forme la méthode.

Le format renvoyé est libre, mais le JSON est de plus en plus utilisé pour sa facilité de lecture et d'utilisation, ainsi que ses performances.
Utilisation en PHP

Pour consommer une API REST en PHP, nous allons utiliser 4 fonctions:

    json_decode(): Permet de transformer un array PHP en json et inversement
    stream_context_create(): Permet de créer un contexte de flux (en gros, ce sont les options relatives à la requête HTTP)
    file_get_contents(): Permet de lire le contenu d'une ressource (en l'occurence ici une ressource distante, l'API, via le contexte de flux)

### GET, Collection

Partons du principe que l'on veut d'abord récupérer toutes les factures disponibles via la méthode /v1/factures, qui devrait nous renvoyer la réponse suivante:
```
GET /v1/factures
200 OK
```
```
[
    {
        "id": 5618,
        "name": "EDF",
        "date": "2016-06-18"
    },
    {
        "id": 6021,
        "name": "Orange",
        "date": "2016-06-22"
    },
    {
        "id": 6129,
        "name": "Amazon",
        "date": "2016-07-02"
    }
]
```
Pour lister les factures dans un `<ul>` on pourrait alors écrire le script suivant:
```
<?php
// Création d'un flux
$opts = array(
    'http' => array(
        'method' => "GET"
    )
);

$context = stream_context_create($opts);

// Récupération du contenu renvoyé par l'API.
$facturesAPI = file_get_contents('http://api.mesfactures.fr/v1/factures', false, $context);

// Transformation de la réponse JSON en Array PHP (sans le second paramètre, le json est transformé en objet)
$factures = json_decode($facturesAPI, true);

// On parcours le tableau à l'aide d'un foreach pour afficher la liste des factures
echo '<ul>';
foreach ($factures as $facture) {
    echo '<li>' . $facture['id'] . ' - ' . $facture['name'] . ' - ' . $facture['date'] . '</li>';
}
echo '</ul>';
```
### PATCH, Instance

Sur la même liste, partons du principe que l'on veut mettre à jour le nom de la facture 6021.
On va utiliser une fonction supplémentaire permettant ici de transformer un array PHP en une chaîne de caractères de paramètres d'url (exemple "name=wf3&theme=php"): `http_build_query()`.

Le script est assez proche :
```
<?php
// Données à mettre à jour
$data = http_build_query(
    array(
        'name' => 'Sosh'
    )
);

// Création d'un flux
$opts = array(
    'http' => array(
        'method' => "PUT",
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $data
    )
);

$context = stream_context_create($opts);

// Exécution de la requête de mise à jour et récupération de la réponse
$facturesAPI = file_get_contents('http://api.mesfactures.fr/v1/factures/6021', false, $context);

// Si la méthode renvoie un code HTTP 200 OK et une réponse ne signalant pas d'erreur, le contenu s'est bien mis à jour.
```
### Aller plus loin

Les deux exemples ci-dessus sont très basiques: ils ne prennent pas en compte la gestion d'erreur (au cas où l'API est indisponible, ou que la facture n'existe pas par exemple).
Vous pouvez également regarder du côté de cURL, très utilisé pour effectuer les requêtes à une API (mais dépendant d'une librairie qui n'est pas systématiquement disponible).

De nombreux sites web mettent à disposition leur API, vous pouvez en essayer une pour vous entrainer.
