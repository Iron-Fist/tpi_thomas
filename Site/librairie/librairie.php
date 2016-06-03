<?php

/**
 * Connexion à la base de donnée db_acj
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