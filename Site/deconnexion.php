<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : deconnexion.php
/* Description  : Page de suppression de session se qui engendre la déconnexion 
/*                de l'utilisateur actuellement entrain d'utiliser le site.
/******************************************************************************/

session_start();
session_destroy();
$_SESSION = array();
header('Location: connexion.php');
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
