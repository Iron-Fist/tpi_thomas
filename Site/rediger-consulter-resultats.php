<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

$est_connecte = false;
$date_jour = date('Y-m-d');

if (!isset($_REQUEST['id_concours_resultats']) && !isset($_REQUEST['id_concours_consulte'])) {
    header('Location: administration.php');
}

if (isset($_REQUEST['remise'])) {
    foreach ($_REQUEST as $key => $value) {

        if (preg_match("#(\d+)_(score)#", $key, $matches)) {
            $id = $matches[1];
            mise_a_jour_score($id, $value, $_REQUEST['id_concours_resultats']);
        }
    }

    header('Location: administration.php');
}

if (isset($_REQUEST['annuler'])) {
    if (isset($_REQUEST['id_concours_consulte']))
        header('Location: mon-compte.php');
    else
        header('Location: administration.php');
}
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
                    <form action="#" method="post">
                        <legend>Remise des résultats : </legend>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th>
                                            Numéro de licence
                                        </th>
                                        <th>
                                            Nom
                                        </th>
                                        <th>
                                            Prénom
                                        </th>
                                        <th>
                                            Score
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_REQUEST['id_concours_resultats']))
                                        liste_participant($_REQUEST['id_concours_resultats']);
                                    else
                                        consulter_concours($_REQUEST['id_concours_consulte']);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        if (isset($_REQUEST['id_concours_resultats']))
                            echo '<input type="submit" class="btn btn-default" name="remise" value="Rendre les résultats">';
                        ?>
                        <input type="submit" class="btn btn-default" name="annuler" value="Annuler">
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
