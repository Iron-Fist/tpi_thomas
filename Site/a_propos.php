<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Accueil</title>
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
                            <li class="active">
                                <a href="a_propos.php">A propos <span class="glyphicon glyphicon-book"></span></a>
                            </li>
                            <li>
                                <?php
                                if (isset($_SESSION['membre_connecte']))
                                    echo '<a href="mon_compte.php">Mon compte <span class="glyphicon glyphicon-user"></span></a>';
                                ?>
                            </li>
                            <li>
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
                    <legend>A propos du club :</legend>
                    Créé en 1999 à JUSSY/Genève (CH).<br>
                    Compte plus de 135 membres actifs et passifs.<br>
                    Ouvert aux archers confirmés et aux débutants.<br>
                    Initiation pour adultes et enfants dès 8 ans.<br>
                    Matériel d'initiation à disposition.<br>
                    Encadrement par des entraineurs diplômés Jeunesse+Sport et des moniteurs confirmés.<br>
                    Entraînement à la compétition.<br>
                    Participation à des compétitions nationales et internationales.<br>
                    Nombreux titres nationaux.<br>
                    Organisateur de concours nationaux et internationaux.<br>
                    Terrain et salle de tir à Jussy.<br>
                    Membre ADAGE (Association Des Archers Genevois)<br>
                    Membre SwissArchery (Swiss Archery Association)
                    
                    <legend>Lieu :</legend>
                    23, route de Juvigny 1254 Jussy
                    
                    <legend>Contact :</legend>
                    <table class="table table-bordered table-striped table-condensed">
                        <tr>
                            <th>Nom / prénom</th>
                            <th>E-mail</th>
                            <th>Fonction</th>
                        </tr>
                        <tr>
                            <td>DE GIULI Jean-Noël</td>
                            <td>president@arc-club-jussy.ch</td>
                            <td>Président</td>
                        </tr>
                        <tr>
                            <td>BOVISI Marco</td>
                            <td>entraineur@arc-club-jussy.ch</td>
                            <td>Directeur sportif</td>
                        </tr>
                        <tr>
                            <td>RUIZ Olivier</td>
                            <td>logistique@arc-club-jussy.ch</td>
                            <td>Directeur logistique</td>
                        </tr>
                    </table>
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
