<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : librairie.php
/* Description  : Page regroupant toutes les fonctions générales.
/******************************************************************************/

/*******************************************************************************
 * Liste des fonctions de cette page :
 * 1) connectDB
 * 2) creer_modale
 * 3) debut_de_page 
 ******************************************************************************/


/**
 * Connexion à la base de donnée db_acj.
 * @staticvar type $maDB
 * @return $maDB\PDO
 */
function connectDB() {
    static $maDB = null;

    try {
        if ($maDB == null) {
            $maDB = new PDO('mysql:host=localhost;dbname=db_acj;charset=utf8', 'root_acj', // username
                    'Super', // mdp
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false));
        }
    } catch (Exception $e) {
        die("Connexion impossible " . $e->getMessage());
    }

    return $maDB;
}

/**
 * Récupère les paramètres pour remplir et créer une modale.
 * @param int $id_modale
 * @param string $titre
 * @param string $contenu
 * @param var $bouton_oui
 */
function creer_modale($id_modale, $titre, $contenu, $bouton_oui) {
    echo '  <div class="modal fade" id="' . $id_modale . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-alert"></span> ' . $titre . '</h4>
            </div>
            <div class="modal-body">' . $contenu . '</div>
            <div class="modal-footer">' . $bouton_oui .'<button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
          </div></div></div></div>';
}

/**
 * Créer le <head> et le debut du <body> de chaqu'une de mes pages html
 * et récupère le paramètre $title pour remplir la balise title.
 * @param string $title
 */
function debut_de_page($title) {
    echo '<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>' .$title . '</title>
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="bootstrap/css/style.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="./image/acj_logo.png">
        <script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
        <script src="./bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <nav id="navigation" class="navbar navbar-default">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-header">
                        <a class="navbar-brand" href="index.php">Arc club Jussy</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">';
}