<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

if(isset($_REQUEST['id_membre_valide'])){
    validation_membre($_REQUEST['id_membre_valide']);
    header('Location: administration-membres.php');
}

if(isset($_REQUEST['id_membre_suppression'])){
    supprimer_membre($_REQUEST['id_membre_suppression']);
    header('Location: administration-membres.php');
}

if(isset($_REQUEST['id_concours_suppression'])){
    supprimer_concours($_REQUEST['id_concours_suppression']);
    header('Location: administration.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
    </body>
</html>
