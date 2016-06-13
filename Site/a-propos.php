<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page('A propos - Arc club Jussy') ?>
    <ul class="nav navbar-nav">
        <li>
            <a href="index.php">Accueil <span class="glyphicon glyphicon-home"></span></a>
        </li>
        <li class="active">
            <a href="a-propos.php">A propos <span class="glyphicon glyphicon-book"></span></a>
        </li>
        <li>
            <?php
            if (isset($_SESSION['membre_connecte']))
                echo '<a href="mon-compte.php">Mon compte <span class="glyphicon glyphicon-user"></span></a>';
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
    <ul class="nav navbar-nav navbar-right">
        <li>
            <a href="aide.php"><b>?</b></a>
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
        <div class="table-responsive">
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
        </div>
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
