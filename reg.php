<?php

Echo "<h1>Les expressions régulières</h1>";

$preg_match('/\//', 'April 15, 2003');
$preg_match('##', 'April 15, 2003');

/http:\/\//
#http://#


$pattern = '/(\w+) \d+, (\d+)/';
$replace = '$1 $2 was a good year';
$subject = 'April 15, 2003';
$newText = preg_replace($pattern, $replace, $subject);


echo $newText;
/*
- commence par <
- début de sous groupe (
  - suivi par une lettre majuscule ou minuscule [A-Za-z]
  - suivi par 0 ou plusieurs lettres majuscules ou minuscules ou chiffres [A-Za-z0-9]*
- fin de sous groupe )
- suivi par une limite de mot \b
- suivi par 0 ou plusieurs caractères sauf > : [^>]*
- suivi par 0 ou plusieurs caractères : .*?
- suivi par <\ : <\/
- suivi par la valeur du sous groupe 1 : \1
- suivi par > :  >
/<([A-Za-z][A-Za-z0-9]*)\b[^>]*>.*?<\/\1>/

*/
