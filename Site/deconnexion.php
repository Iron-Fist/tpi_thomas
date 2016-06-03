<?php
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
