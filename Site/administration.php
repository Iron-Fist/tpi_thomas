<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

if (isset($_REQUEST['administration_membres'])) {
    header('Location: administration-membres.php');
}

if (isset($_REQUEST['creer_concours'])) {
    header('Location: creer-modifier-concours.php');
}

$date_jour = date('Y-m-d');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Administration</title>
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="bootstrap/css/tuto.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
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
                        <a class="navbar-brand">Arc club Jussy</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="index.php">Accueil <span class="glyphicon glyphicon-home"></span></a>
                            </li>
                            <li>
                                <a href="a-propos.php">A propos <span class="glyphicon glyphicon-book"></span></a>
                            </li>
                            <li>
                                <?php
                                if (isset($_SESSION['membre_connecte']))
                                    echo '<a href="mon-compte.php">Mon compte <span class="glyphicon glyphicon-user"></span></a>';
                                ?>
                            </li>
                            <li class="active">
                                <?php
                                if (isset($_SESSION['membre_connecte'])) {
                                    if ($_SESSION['membre_connecte']['est_admin'])
                                        echo '<a href="administration.php">Administration <span class="glyphicon glyphicon-wrench"></span></a>';
                                }
                                ?>
                            </li>
                            <li>
                                <?php
                                if (isset($_SESSION['membre_connecte']))
                                    echo '<a href="deconnexion.php">Deconnexion <span class="glyphicon glyphicon-log-out"></span></a>';
                                else
                                    echo '<a href="connexion.php">Connexion <span class="glyphicon glyphicon-log-in"></span></a>';
                                ?>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="row">
                <section class="col-sm-12 table-responsive">
                    <form action="#" method="post">
                        <legend>Administration des concours</legend>
                        
                        <input type="submit" class="btn btn-default" name="administration_membres" value="Administration membres">
                        
                        <table class="table table-bordered table-striped table-condensed">
                            <caption>
                                Liste des futurs concours :
                            </caption>
                            <thead>
                                <tr>
                                    <th>
                                        Intitule
                                    </th>
                                    <th>
                                        Lieu
                                    </th>
                                    <th>
                                        Nombre de places
                                    </th>
                                    <th>
                                        Date du concours
                                    </th>
                                    <th>
                                        Modification
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php tableau_futur_concours($date_jour); ?>
                            </tbody>
                        </table>

                        <table class="table table-bordered table-striped table-condensed">
                            <caption>
                                Liste des concours en attente de résultats :
                            </caption>
                            <thead>
                                <tr>
                                    <th>
                                        Intitule
                                    </th>
                                    <th>
                                        Lieu
                                    </th>
                                    <th>
                                        Date du concours
                                    </th>
                                    <th>
                                        Résultats
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php tableau_concours_attente_resultats($date_jour); ?>
                            </tbody>
                        </table>
                        
                        <input type="submit" class="btn btn-default" name="creer_concours" value="Créer un Concours">
                    </form>
                </section>
            </div>
            <div class="row">
                <footer class="col-sm-12">
                    &copy; Thomas Carreira
                </footer>
            </div>
        </div>
    </body>
</html>
