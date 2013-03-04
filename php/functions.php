<?php 

// Supprime les accents
function rmAccents($str, $charset='utf-8')
// src : http://www.weirdog.com/blog/php/supprimer-les-accents-des-caracteres-accentues.html
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
}

function selChar($possible){
	return substr($possible, mt_rand(0, strlen($possible)-1), 1);
}

?> 
