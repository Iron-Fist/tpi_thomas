<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : mon-compte.php
/* Description  : Page propre à chaque membre qui lui permet de gérer
/*                ses inscription et de consulter ses concours.
/******************************************************************************/

session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

if (!isset($_SESSION['membre_connecte'])) {
    header('Location: connexion.php');
}
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page('Mon compte - Arc club Jussy') ?>
    <ul class="nav navbar-nav">
        <li>
            <a href="index.php">Accueil <span class="glyphicon glyphicon-home"></span></a>
        </li>
        <li>
            <a href="a-propos.php">A propos <span class="glyphicon glyphicon-book"></span></a>
        </li>
        <li class="active">
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
        <legend>Mon compte</legend>

        <h5>Liste des concours auxquels je suis inscrit :</h5>    
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed">
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
                            Date limite des inscriptions
                        </th>
                        <th>
                            Désinscription
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    tableau_futur_concours_inscrits($_SESSION['membre_connecte']['id_membre']);
                    ?>
                </tbody>
            </table>
        </div>

        <h5>Liste des concours auxquels j'ai participé :</h5>    
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed">
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
                    <?php
                    tableau_concours_passe_inscrits($_SESSION['membre_connecte']['id_membre']);
                    ?>
                </tbody>
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
