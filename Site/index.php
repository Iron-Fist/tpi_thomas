<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page('Arc club Jussy') ?>
                        <ul class="nav navbar-nav">
                            <li class="active">
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
                    <legend>Présentation : </legend>
                    <p>
                        Bienvenue sur le site officiel de l'arc club Jussy.<br>
                        Ce site va vous permettre de vous tenir facilement au courant sur les prochains concours à venir<br>
                        et vous pourrez également vous y inscrire.
                    </p>

                    <legend>Liste des futurs concours :</legend>
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
                                    <?php
                                    if (isset($_SESSION['membre_connecte']))
                                        echo '<th>Inscription</th>';
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                tableau_futur_concours_inscription(isset($_SESSION['membre_connecte']) ? $_SESSION['membre_connecte']["id_membre"] : -1);
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
