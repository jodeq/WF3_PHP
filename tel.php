<?php

$personnes = array(
    '0' => array(
        'nom' => 'Dupont',
        'prenom' => 'Pierre',
        'email' => 'pierre.d@gmail.com',
        'telephones' => array(
            'fixe' => '03 00 00 00 00',
            'portable' => '06 00 00 00 00'
        )
    ),
    '1' => array(
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'email' => 'jean.d@gmail.com',
        'telephones' => array(
            'fixe' => '03 00 00 00 00',
            'portable' => '06 00 00 00 00'
        )
    ),
    '2' => array(
        'nom' => 'Dupont',
        'prenom' => 'Marie',
        'email' => 'marie.d@gmail.com',
    ),
  );

  foreach($personnes as $personne) {
    echo 'Nom : '.$personne['nom'].'<br/>';
    echo 'Prénom : '.$personne['prenom'].'<br/>';
    echo 'Email : '.$personne['email'].'<br/>';

    // On vérifie l'existence de la cellule des téléphones
    if (isset($personne['telephones'])) {
        foreach($personne['telephones'] as $type => $telephone) {
            echo 'Téléphone ' . $type . ' : '.$telephone.'<br/>';
        }
    }
  }

?>
