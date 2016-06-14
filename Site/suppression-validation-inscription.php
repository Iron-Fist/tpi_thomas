<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : suppression-validation-inscription.php
/* Description  : Page qui récupère l'id qui est inscrit dans la barre 
/*                de recherche pour soit: valider, supprimer, inscrire, 
/*                désinscrire un membre ou encore supprimer un concours.
/******************************************************************************/

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
    desinscrire_membres_du_concours_supprimer($_REQUEST['id_concours_suppression']);
    supprimer_concours($_REQUEST['id_concours_suppression']);
    header('Location: administration.php');
}

if(isset($_REQUEST['id_concours_inscription'])){
    inscription_concours($_REQUEST['id_concours_inscription'], $_SESSION['membre_connecte']['id_membre']);
    header('Location: mon-compte.php');
}

if(isset($_REQUEST['id_concours_desinscription'])){
    desinscription_concours($_REQUEST['id_concours_desinscription'], $_SESSION['membre_connecte']['id_membre']);
    header('Location: index.php');
}

if(!isset($_REQUEST)){
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
