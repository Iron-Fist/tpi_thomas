<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

if (!isset($_SESSION['membre_connecte']['est_admin']) || $_SESSION['membre_connecte']['est_admin'] == 0) {
    header('Location: mon-compte.php');
}

if (isset($_REQUEST['administration_membres'])) {
    header('Location: administration-membres.php');
}

if (isset($_REQUEST['creer_concours'])) {
    header('Location: creer-modifier-concours.php');
}
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page('Administration - Arc club Jussy') ?>
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

                        <h5>Liste des futurs concours :</h5>
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
                                            Modification
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tableau_futur_concours(); ?>
                                </tbody>
                            </table>
                        </div>

                        <h5>Liste des concours en attente de résultats :</h5>
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
                                    <?php tableau_remise_resultats_concours(); ?>
                                </tbody>
                            </table>
                        </div>

                        <h5>Liste des concours avec résultats :</h5>    
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
                                    <?php tableau_modifier_resultats_concours(); ?>
                                </tbody>
                            </table>
                        </div>
                        <input type="submit" class="btn btn-default" name="creer_concours" value="Créer un Concours">
                    </form>
                    <br>
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
